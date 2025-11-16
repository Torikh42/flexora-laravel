<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudioClassController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('classes', StudioClassController::class);
Route::resource('memberships', MembershipController::class);
Route::resource('enrollments', EnrollmentController::class);

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');
