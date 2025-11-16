<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth('api')->user() ?? auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'schedule_id' => 'required|integer|exists:schedules,id',
        ]);

        $scheduleId = $request->input('schedule_id');

        $exists = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('schedule_id', $scheduleId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already enrolled'], 409);
        }

        $enrollment = \App\Models\Enrollment::create([
            'user_id' => $user->id,
            'schedule_id' => $scheduleId,
            'status' => 'confirmed',
        ]);

        return response()->json(['message' => 'Enrolled successfully', 'enrollment' => $enrollment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
