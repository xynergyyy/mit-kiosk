<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display the visitor kiosk view (2D map).
     */
    public function index()
    {
        $waypoints = Waypoint::with(['assignedUsers', 'assignedUser'])->get();
        $staffMembers = \App\Models\User::whereIn('role', ['faculty', 'principal', 'guidance_counselor'])->get();
        return view('visitor.kiosk', compact('waypoints', 'staffMembers'));
    }

    /**
     * Display the 3D panoramic view for a specific waypoint.
     */
    public function panorama(Waypoint $waypoint)
    {
        $waypoint->load(['assignedUsers']);

        // Load all OTHER waypoints that also have a pano_url (for in-panorama hotspots)
        $hotspots = Waypoint::where('id', '!=', $waypoint->id)
            ->whereNotNull('pano_url')
            ->where('pano_url', '!=', '')
            ->get(['id', 'name', 'location_code', 'pano_url']);

        return view('visitor.panorama', compact('waypoint', 'hotspots'));
    }

    public function queueWindow(Waypoint $waypoint)
    {
        $waypoint->load(['assignedUsers']);
        return view('visitor.queue', compact('waypoint'));
    }

    public function queueAllWindow()
    {
        return view('visitor.queue', ['waypoint' => null]);
    }
}
