<?php

namespace App\Http\Controllers;

use App\Models\StudioClass;
use Illuminate\Http\Request;

use App\Models\Membership;

class StudioClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studio_classes = StudioClass::with('schedules')->get();
        $memberships = Membership::all();
        return view('studio_class', [
            'studio_classes' => $studio_classes,
            'memberships' => $memberships,
        ]);
    }

    /**
     * API: Get available schedules for a specific date
     */
    public function availableByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'class_id' => 'nullable|integer|exists:studio_classes,id'
        ]);

        $date = $request->query('date');
        $classId = $request->query('class_id');
        
        $query = \App\Models\Schedule::whereDate('start_time', $date)
            ->with('studioClass');
        
        // Filter by class_id if provided
        if ($classId) {
            $query->where('studio_class_id', $classId);
        }
        
        $schedules = $query->get();

        return response()->json(['schedules' => $schedules], 200);
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
        //
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
