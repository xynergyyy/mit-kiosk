<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KioskPrintController extends Controller
{
    public function ticket(Request $request)
    {
        $validated = $request->validate([
            'display_number' => 'required|string|max:20',
            'meta' => 'required|string|max:200',
        ]);

        if (!config('printer.enabled')) {
            return response()->json([
                'message' => 'Printing is disabled.',
                'print' => [
                    'attempted' => false,
                    'ok' => false,
                    'method' => null,
                    'target' => null,
                    'error' => 'Printing is disabled.',
                ],
            ], 409);
        }

        $print = [
            'attempted' => true,
            'ok' => null,
            'method' => null,
            'target' => null,
            'error' => null,
        ];

        try {
            $result = app(\App\Services\PrinterService::class)->printTicket(
                (string) $validated['display_number'],
                (string) $validated['meta'],
                (string) config('printer.pc_name'),
                (string) config('printer.share_name')
            );

            if (is_array($result)) {
                $print['ok'] = $result['ok'] ?? null;
                $print['method'] = $result['method'] ?? null;
                $print['target'] = $result['target'] ?? null;
                $print['error'] = $result['error'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error("Kiosk Ticket Print Error: " . $e->getMessage(), [
                'display_number' => $validated['display_number'],
                'exception' => $e
            ]);
            $print['ok'] = false;
            $print['error'] = 'Printer connection failure. Please contact administrator.';
        }

        return response()->json([
            'print' => $print,
        ], $print['ok'] ? 200 : 500);
    }
}

