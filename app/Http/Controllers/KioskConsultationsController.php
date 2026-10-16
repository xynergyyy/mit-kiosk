<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Waypoint;
use App\Models\User;
use Illuminate\Http\Request;

class KioskConsultationsController extends Controller
{
    private function ticketOffsetForRole(?string $role): int
    {
        if ($role === 'principal') {
            return 100;
        }
        if ($role === 'guidance_counselor') {
            return 200;
        }
        return 0;
    }

    private function displayTicketNumber(int $ticketNumber, ?string $role): string
    {
        $n = $ticketNumber + $this->ticketOffsetForRole($role);
        return str_pad((string) $n, 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waypoint_id' => 'nullable|exists:waypoints,id',
            'assigned_user_id' => 'required|exists:users,id',
            'purpose_code' => 'required|string|max:100',
            'purpose_other_text' => 'nullable|string|max:1000',
        ]);

        $waypointId = $validated['waypoint_id'] ?? null;
        $assigned = User::findOrFail((int) $validated['assigned_user_id']);
        
        if ($assigned->availability_status !== 'available') {
            return response()->json(['message' => 'Staff not available for consultation.'], 409);
        }

        $maxToday = Consultation::query()
            ->where('assigned_user_id', $assigned->id)
            ->whereDate('created_at', now()->toDateString())
            ->max('ticket_number');

        $ticket = (int) ($maxToday ?? 0) + 1;

        $consultation = Consultation::create([
            'assigned_user_id' => $assigned->id,
            'waypoint_id' => $waypointId,
            'ticket_number' => $ticket,
            'purpose_code' => $validated['purpose_code'],
            'purpose_other_text' => $validated['purpose_other_text'] ?? null,
            'status' => 'waiting',
        ]);

        $displayNumber = $this->displayTicketNumber((int) $consultation->ticket_number, $assigned->role);

        $print = [
            'attempted' => false,
            'ok' => null,
            'method' => null,
            'target' => null,
            'error' => null,
        ];

        // Optionally send a print job to the backend configured printer
        if (config('printer.enabled')) {
            $locationName = $consultation->waypoint ? $consultation->waypoint->name : 'Office';
            $meta = $assigned->name . ' - ' . $locationName;

            $print['attempted'] = true;
            try {
                $result = app(\App\Services\PrinterService::class)->printTicket(
                    $displayNumber, 
                    $meta, 
                    config('printer.pc_name'), 
                    config('printer.share_name')
                );

                if (is_array($result)) {
                    $print['ok'] = $result['ok'] ?? null;
                    $print['method'] = $result['method'] ?? null;
                    $print['target'] = $result['target'] ?? null;
                    $print['error'] = $result['error'] ?? null;
                }

                if ($print['ok'] === false) {
                    \Illuminate\Support\Facades\Log::warning('Printer failed', [
                        'method' => $print['method'],
                        'target' => $print['target'],
                        'error' => $print['error'],
                    ]);
                }
            } catch (\Exception $e) {
                // Log or ignore print error so we don't break the flow
                \Illuminate\Support\Facades\Log::error("Printer error: " . $e->getMessage());
                $print['ok'] = false;
                $print['error'] = 'Printer service unavailable. Please check physical connection.';
            }
        }

        return response()->json([
            'id' => $consultation->id,
            'ticket_number' => str_pad((string) $consultation->ticket_number, 3, '0', STR_PAD_LEFT),
            'display_ticket_number' => $displayNumber,
            'created_at' => $consultation->created_at,
            'print' => $print,
        ], 201);
    }
}
