<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WaypointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Waypoint::with(['assignedUsers'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_code' => 'required|string|unique:waypoints',
            'description' => 'nullable|string',
            'floor' => 'required|string',
            'image_url' => 'nullable|string|max:2048',
            'pano_url' => 'nullable|string|max:2048',
            'assigned_user_ids' => 'nullable|array',
            'assigned_user_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->whereIn('role', ['faculty', 'principal', 'guidance_counselor']),
            ],
            'coordinates_x' => 'required|numeric',
            'coordinates_y' => 'required|numeric',
        ]);

        $assignedUserIds = $validated['assigned_user_ids'] ?? [];
        unset($validated['assigned_user_ids']);

        $waypoint = Waypoint::create($validated);
        $waypoint->assignedUsers()->sync($assignedUserIds);

        return response()->json($waypoint->load('assignedUsers'), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Waypoint $waypoint)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location_code' => 'sometimes|string|unique:waypoints,location_code,'.$waypoint->id,
            'description' => 'nullable|string',
            'floor' => 'sometimes|string',
            'image_url' => 'nullable|string|max:2048',
            'pano_url' => 'nullable|string|max:2048',
            'assigned_user_ids' => 'nullable|array',
            'assigned_user_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->whereIn('role', ['faculty', 'principal', 'guidance_counselor']),
            ],
            'coordinates_x' => 'sometimes|numeric',
            'coordinates_y' => 'sometimes|numeric',
        ]);

        $assignedUserIds = $validated['assigned_user_ids'] ?? null;
        unset($validated['assigned_user_ids']);

        $waypoint->update($validated);
        if (is_array($assignedUserIds)) {
            $waypoint->assignedUsers()->sync($assignedUserIds);
        }

        return response()->json($waypoint->load('assignedUsers'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Waypoint $waypoint)
    {
        $waypoint->delete();
        return response()->json(null, 204);
    }
}
