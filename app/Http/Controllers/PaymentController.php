<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Membership;
use App\Models\UserMembership;
use Carbon\Carbon;

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

        // Verify that user has an enrollment for this schedule (pending or confirmed)
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('schedule_id', $scheduleId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->first();

        if (!$enrollment) {
            // If no enrollment found, create it as pending
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'schedule_id' => $scheduleId,
                'status' => 'pending',
                'enrollment_type' => 'paid',
            ]);
        }

        return view('invoice_kelas', [
            'schedule' => $schedule,
            'enrollment' => $enrollment,
            'user' => $user
        ]);
    }

    /**
     * Show payment page for a membership
     */
    public function showMembershipPayment(Request $request, Membership $membership)
    {
        // Try to get user dari berbagai sumber:
        // 1. API guard (JWT token di header)
        $user = Auth::guard('api')->user();
        
        // 2. Web guard (session)
        if (!$user) {
            $user = Auth::guard('web')->user();
        }
        
        // 3. Fallback ke default
        if (!$user) {
            $user = Auth::user();
        }
        
        // If still no user, redirect to login
        if (!$user) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Check if user already has an active membership
        $activeMembership = $user->userMemberships()
            ->where('end_date', '>=', Carbon::today())
            ->first();
        
        if ($activeMembership) {
            return redirect('/')->with('error', 'Anda sudah memiliki membership yang aktif hingga ' . $activeMembership->end_date->format('d-m-Y') . '. Silakan tunggu hingga berakhir untuk membeli membership baru.');
        }

        return view('pay_membership', [
            'membership' => $membership,
            'user' => $user
        ]);
    }

    /**
     * Process the payment for a membership and create the user membership
     */
    public function processMembershipPayment(Request $request, Membership $membership)
    {
        // Try to get user dari berbagai sumber:
        // 1. API guard (JWT token di header)
        $user = Auth::guard('api')->user();
        
        // 2. Jika ada token di POST body/query, validate dan gunakan
        if (!$user && $request->has('token')) {
            try {
                $token = $request->input('token');
                // Validate token manually
                $payload = \JWTAuth::parseToken()->getPayload();
                $user = User::find($payload->get('sub'));
            } catch (\Exception $e) {
                \Log::warning('Invalid JWT token in POST body: ' . $e->getMessage());
            }
        }
        
        // 3. Web guard (session)
        if (!$user) {
            $user = Auth::guard('web')->user();
        }
        
        // 4. Fallback ke default
        if (!$user) {
            $user = Auth::user();
        }
        
        // Debug: Log untuk lihat user detection
        \Log::info('processMembershipPayment - User detection:', [
            'api_user' => Auth::guard('api')->user() ? Auth::guard('api')->user()->id : null,
            'post_token_user' => $user ? $user->id : null,
            'web_user' => Auth::guard('web')->user() ? Auth::guard('web')->user()->id : null,
        ]);
        
        // If still no user, redirect to login
        if (!$user) {
            \Log::warning('processMembershipPayment - No user found, redirecting to login');
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Check if user already has an active membership
        $activeMembership = $user->userMemberships()
            ->where('end_date', '>=', Carbon::today())
            ->first();
        
        if ($activeMembership) {
            return redirect('/')->with('error', 'Anda sudah memiliki membership yang aktif hingga ' . $activeMembership->end_date->format('d-m-Y') . '. Silakan tunggu hingga berakhir untuk membeli membership baru.');
        }

        // Here you would typically process a real payment.
        // For now, we will just create the membership directly.

        $userMembership = new UserMembership();
        $userMembership->user_id = $user->id;
        $userMembership->membership_id = $membership->id;
        $userMembership->start_date = Carbon::now();
        $userMembership->end_date = Carbon::now()->addDays($membership->duration_days);
        $userMembership->save();

        return redirect()->route('home')->with('success', 'Anda berhasil membeli membership.');
    }
}
