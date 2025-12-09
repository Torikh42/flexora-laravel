<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\UserMembership;
use App\Models\StudioClass;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_classes' => StudioClass::count(),
            'total_enrollments' => Enrollment::count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'confirmed_enrollments' => Enrollment::where('status', 'confirmed')->count(),
            'active_memberships' => UserMembership::where('end_date', '>=', now())->count(),
        ];

        // Get recent enrollments (5 latest)
        $recentEnrollments = Enrollment::with(['user', 'schedule.studioClass'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentEnrollments'));
    }
}
