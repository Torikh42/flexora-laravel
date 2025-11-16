<?php

namespace App\Http\Controllers;

use App\Models\StudioClass;
use App\Models\Schedule;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\StudioClass  $class
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function create(StudioClass $class, Schedule $schedule)
    {
        return view('booking.create', [
            'class' => $class,
            'schedule' => $schedule,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $enrollment = new Enrollment();
        $enrollment->user_id = auth()->id();
        $enrollment->schedule_id = $request->schedule_id;
        $enrollment->save();

        return redirect()->route('home')->with('success', 'You have successfully enrolled in the class.');
    }
}
