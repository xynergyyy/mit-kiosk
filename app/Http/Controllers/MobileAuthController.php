<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MobileAuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        if (!in_array($user->role, ['faculty', 'principal', 'guidance_counselor', 'admin'], true)) {
            return response()->json(['message' => 'This account is not allowed to access this app.'], 403);
        }

        $plain = bin2hex(random_bytes(32));
        $user->api_token_hash = hash('sha256', $plain);
        $user->api_token_expires_at = now()->addDays(30);
        $user->save();

        $time = Carbon::now()->format('h:i A');
        $prefix = $user->isAdmin() ? "Admin User" : "Staff";
        ActivityLog::record('login', "{$prefix} Logged In at {$time} - {$user->name}", ['client' => 'mobile'], $user);

        return response()->json([
            'token' => $plain,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'availability_status' => $user->availability_status,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'availability_status' => $user->availability_status,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $time = Carbon::now()->format('h:i A');
            $prefix = $user->isAdmin() ? "Admin User" : "Staff";
            ActivityLog::record('logout', "{$prefix} Logged Out at {$time} - {$user->name}", ['client' => 'mobile'], $user);

            $user->api_token_hash = null;
            $user->api_token_expires_at = null;
            $user->save();
        }

        return response()->json(['ok' => true]);
    }
}
