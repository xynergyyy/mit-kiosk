<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use App\Models\User;
use Illuminate\Http\Request;

class KioskStatusController extends Controller
{
    /**
     * Get the latest status for waypoints and staff for polling.
     */
    public function index()
    {
        // Fetch same data as VisitorController@index but return as JSON
        $waypoints = Waypoint::with(['assignedUsers', 'assignedUser'])->get();
        $staffMembers = User::whereIn('role', ['faculty', 'principal', 'guidance_counselor'])->get();

        return response()->json([
            'waypoints' => $waypoints,
            'staffMembers' => $staffMembers,
        ]);
    }
}
