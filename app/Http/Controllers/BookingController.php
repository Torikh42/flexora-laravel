<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this class.');
        }

        // Check for active membership
        if ($user->hasActiveMembership()) {
            $activeMembership = $user->userMemberships()->where('end_date', '>=', now())->first();

            if ($activeMembership && $schedule->start_time->between($activeMembership->start_date, $activeMembership->end_date->endOfDay())) {
                $enrollment = new Enrollment();
                $enrollment->user_id = $user->id;
                $enrollment->schedule_id = $schedule->id;
                $enrollment->save();

                return redirect()->route('home')->with('success', 'You have successfully enrolled in the class using your membership.');
            } else {
                return back()->with('error', 'The selected class date is outside your active membership period.');
            }
        } else {
            // Redirect to payment page for non-members
            return redirect()->to('/bayar-kelas/' . $schedule->id);
        }
    }
}
