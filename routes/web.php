<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudioClassController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('studio_classes', StudioClassController::class);
Route::resource('memberships', MembershipController::class);
Route::resource('enrollments', EnrollmentController::class);

// Payment and Invoice routes
Route::middleware('jwt.auth')->group(function () {
    Route::get('/bayar-kelas/{schedule}', [PaymentController::class, 'bayarKelas'])->name('bayar_kelas');
    Route::get('/invoice-kelas/{schedule}', [PaymentController::class, 'invoiceKelas'])->name('invoice_kelas');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

// Dashboard routes - aksesible untuk semua, proteksi via JavaScript
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

use App\Http\Controllers\BookingController;

Route::get('/booking/{class}/{schedule}', [BookingController::class, 'create'])->name('booking.create');

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::post('/memberships/{membership}/purchase', [MembershipController::class, 'purchase'])->name('memberships.purchase');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');
