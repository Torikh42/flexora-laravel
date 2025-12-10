<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudioClassController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;

// ============= HOME & AUTH ROUTES =============
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

// ============= WEB LOGIN (for Admin Panel) =============
use App\Http\Controllers\AuthController;
Route::post('/web-login', [AuthController::class, 'webLogin'])->name('web.login');

// ============= DASHBOARD ROUTE =============
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ============= RESOURCE ROUTES =============
Route::resource('studio_classes', StudioClassController::class);
Route::resource('enrollments', EnrollmentController::class);

// ============= PAYMENT ROUTES =============
Route::get('/bayar-kelas/{schedule}', [PaymentController::class, 'bayarKelas'])->name('bayar_kelas');
Route::get('/invoice-kelas/{schedule}', [PaymentController::class, 'invoiceKelas'])->name('invoice_kelas');
Route::get('/memberships/{membership}/payment', [PaymentController::class, 'showMembershipPayment'])->name('memberships.payment');
Route::post('/memberships/{membership}/process-payment', [PaymentController::class, 'processMembershipPayment'])->name('memberships.processPayment');

// ============= BOOKING ROUTES =============
Route::get('/booking/{class}/{schedule}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// ============= MEMBERSHIP PURCHASE ROUTE =============
Route::post('/memberships/{membership}/purchase', [MembershipController::class, 'purchase'])->name('memberships.purchase');

// ============= CONTACT ROUTE =============
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// ============= ADMIN ROUTES =============
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminMembershipController;
use App\Http\Controllers\Admin\AdminScheduleController;

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('classes', AdminClassController::class);
    Route::resource('memberships', AdminMembershipController::class);
    Route::resource('schedules', AdminScheduleController::class);
});
