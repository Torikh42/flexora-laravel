<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Enrollment;

class PaymentController extends Controller
{
    /**
     * Show payment page for class enrollment
     */
    public function bayarKelas($scheduleId)
    {
        // Get user from either web session or API token
        $user = Auth::user() ?? Auth::guard('api')->user();
        
        if (!$user) {
            return redirect('/login');
        }

        $schedule = Schedule::with('studioClass')->findOrFail($scheduleId);
        
        return view('bayar_kelas', [
            'schedule' => $schedule,
            'user' => $user
        ]);
    }

    /**
     * Show invoice after successful enrollment
     */
    public function invoiceKelas($scheduleId)
    {
        // Get user from either web session or API token
        $user = Auth::user() ?? Auth::guard('api')->user();
        
        if (!$user) {
            return redirect('/login');
        }

        $schedule = Schedule::with('studioClass')->findOrFail($scheduleId);

        // Verify that user has enrolled for this schedule
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('schedule_id', $scheduleId)
            ->first();

        if (!$enrollment) {
            abort(403, 'Anda belum mendaftar untuk kelas ini.');
        }

        return view('invoice_kelas', [
            'schedule' => $schedule,
            'enrollment' => $enrollment,
            'user' => $user
        ]);
    }
}
