<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileProfileController extends Controller
{
    public function updateAvailability(Request $request)
    {
        $validated = $request->validate([
            'availability_status' => 'required|in:available,away,busy',
        ]);

        $user = $request->user();
        $user->availability_status = $validated['availability_status'];
        $user->save();

        $statusText = ucfirst($validated['availability_status']);
        $prefix = $user->isAdmin() ? "Admin User" : "Staff";
        ActivityLog::record(
            'change_status',
            "{$prefix} Changed their Status to {$statusText}. - {$user->name}",
            ['status' => $validated['availability_status']],
            $user
        );

        return response()->json([
            'availability_status' => $user->availability_status,
        ]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->password = $validated['new_password'];
        $user->save();

        $prefix = $user->isAdmin() ? "Admin User" : "Staff";
        ActivityLog::record(
            'change_password',
            "{$prefix} Changed their Password - {$user->name}",
            null,
            $user
        );

        return response()->json(['ok' => true]);
    }
}
