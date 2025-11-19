<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
     * Get user's enrollments with memberships (for dashboard)
     */
    public function getUserEnrollments()
    {
        $user = auth('api')->user() ?? auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Get enrollments with schedule details
        $enrollments = $user->enrollments()
            ->with(['schedule.studioClass', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($enrollment) {
                $imageUrl = null;
                if ($enrollment->schedule->studioClass->image) {
                    $imageUrl = asset('images/' . $enrollment->schedule->studioClass->image);
                }
                
                return [
                    'id' => $enrollment->id,
                    'class_name' => $enrollment->schedule->studioClass->name,
                    'class_slug' => $enrollment->schedule->studioClass->slug,
                    'class_image' => $imageUrl,
                    'instructor' => $enrollment->schedule->instructor ?? 'Instructor',
                    'schedule_date' => Carbon::parse($enrollment->schedule->start_time)->format('Y-m-d'),
                    'schedule_time' => Carbon::parse($enrollment->schedule->start_time)->format('H:i'),
                    'status' => $enrollment->status,
                    'enrollment_type' => $enrollment->enrollment_type,
                    'created_at' => Carbon::parse($enrollment->created_at)->format('d M Y'),
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
                ];
            });

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'enrollments' => $enrollments,
            'active_memberships' => $activeMemberships,
            'total_enrollments' => $enrollments->count(),
            'confirmed_count' => $enrollments->where('status', 'confirmed')->count(),
            'pending_count' => $enrollments->where('status', 'pending')->count(),
        ]);
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
        $schedule = \App\Models\Schedule::findOrFail($scheduleId);

        // Check if user is already enrolled (any status)
        $exists = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('schedule_id', $scheduleId)
            ->first();

        if ($exists) {
            // If already has pending or confirmed enrollment, reject
            if ($exists->status === 'confirmed') {
                return response()->json(['message' => 'Anda sudah terdaftar di kelas ini'], 409);
            } elseif ($exists->status === 'pending') {
                return response()->json(['message' => 'Enrollment sedang dalam proses pembayaran'], 409);
            }
        }

        // Check if user has active membership
        $activeMembership = $user->userMemberships()
            ->where('end_date', '>=', now())
            ->first();

        if ($activeMembership) {
            // User has active membership - validate schedule date is within membership period
            $scheduleDate = Carbon::parse($schedule->start_time)->toDateString();
            $membershipStart = $activeMembership->start_date;
            $membershipEnd = $activeMembership->end_date;

            if ($scheduleDate < $membershipStart || $scheduleDate > $membershipEnd) {
                return response()->json([
                    'message' => 'Class is outside your membership period',
                    'membership_end_date' => $membershipEnd
                ], 409);
            }

            // Create enrollment as free (via membership)
            $enrollment = \App\Models\Enrollment::create([
                'user_id' => $user->id,
                'schedule_id' => $scheduleId,
                'status' => 'confirmed',
                'enrollment_type' => 'free_membership',
            ]);

            return response()->json([
                'message' => 'Enrolled successfully via membership',
                'enrollment' => $enrollment,
                'enrollment_type' => 'free_membership'
            ], 201);
        } else {
            // User doesn't have active membership - create enrollment with pending status
            // Status will be updated to confirmed after payment
            $enrollment = \App\Models\Enrollment::create([
                'user_id' => $user->id,
                'schedule_id' => $scheduleId,
                'status' => 'pending',
                'enrollment_type' => 'paid',
            ]);

            return response()->json([
                'message' => 'Payment required',
                'enrollment' => $enrollment,
                'enrollment_type' => 'paid'
            ], 402);
        }
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
