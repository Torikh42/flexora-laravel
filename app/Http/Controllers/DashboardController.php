<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user's enrollment dashboard
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        // Get all user enrollments with schedule and class details
        $enrollments = $user->enrollments()
            ->with([
                'schedule.studioClass',
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'class_name' => $enrollment->schedule->studioClass->name,
                    'class_slug' => $enrollment->schedule->studioClass->slug,
                    'instructor' => $enrollment->schedule->instructor ?? 'Instructor',
                    'schedule_date' => Carbon::parse($enrollment->schedule->start_time)->format('Y-m-d'),
                    'schedule_time' => Carbon::parse($enrollment->schedule->start_time)->format('H:i'),
                    'status' => $enrollment->status, // pending or confirmed
                    'enrollment_type' => $enrollment->enrollment_type, // free_membership or paid
                    'created_at' => Carbon::parse($enrollment->created_at)->format('d M Y'),
                    'full_datetime' => $enrollment->schedule->start_time,
                ];
            });

        // Get active memberships
        $activeMemberships = $user->userMemberships()
            ->where('end_date', '>=', Carbon::today())
            ->with('membership')
            ->get()
            ->map(function ($userMembership) {
                return [
                    'id' => $userMembership->id,
                    'membership_name' => $userMembership->membership->name,
                    'start_date' => Carbon::parse($userMembership->start_date)->format('d M Y'),
                    'end_date' => Carbon::parse($userMembership->end_date)->format('d M Y'),
                    'days_remaining' => Carbon::parse($userMembership->end_date)->diffInDays(Carbon::today()),
                    'end_date_raw' => $userMembership->end_date,
                ];
            });

        return view('dashboard', [
            'user' => $user,
            'enrollments' => $enrollments,
            'activeMemberships' => $activeMemberships,
        ]);
    }

    /**
     * Get enrollments by status (for filtering)
     */
    public function getEnrollmentsByStatus($status)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // status: pending, confirmed, or all
        $query = $user->enrollments()
            ->with('schedule.studioClass');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $enrollments = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'class_name' => $enrollment->schedule->studioClass->name,
                    'instructor' => $enrollment->schedule->instructor ?? 'Instructor',
                    'schedule_date' => Carbon::parse($enrollment->schedule->start_time)->format('Y-m-d'),
                    'schedule_time' => Carbon::parse($enrollment->schedule->start_time)->format('H:i'),
                    'status' => $enrollment->status,
                    'enrollment_type' => $enrollment->enrollment_type,
                    'created_at' => $enrollment->created_at,
                ];
            });

        return response()->json([
            'enrollments' => $enrollments,
            'count' => $enrollments->count(),
        ]);
    }
}
