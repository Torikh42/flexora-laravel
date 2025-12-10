<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\StudioClass;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('studioClass')->orderBy('start_time', 'desc')->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classes = StudioClass::all();
        return view('admin.schedules.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'studio_class_id' => 'required|exists:studio_classes,id',
            'instructor' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $classes = StudioClass::all();
        return view('admin.schedules.edit', compact('schedule', 'classes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'studio_class_id' => 'required|exists:studio_classes,id',
            'instructor' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
