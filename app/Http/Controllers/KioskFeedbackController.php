<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class KioskFeedbackController extends Controller
{
    /**
     * Store visitor feedback in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|string|in:Very Satisfied,Satisfied,Neutral,Dissatisfied,Very Dissatisfied',
            'suggestions' => 'nullable|string|max:2000',
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!',
            'data' => $feedback,
        ], 201);
    }
}
