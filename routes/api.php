<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () use ($router) {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('user-profile', [AuthController::class, 'userProfile']);
    });
});

// Enrollments and membership purchase API
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\StudioClassController;

Route::get('classes/available-by-date', [StudioClassController::class, 'availableByDate']);

Route::middleware('auth:api')->group(function () {
    Route::post('enrollments', [EnrollmentController::class, 'store']);
    Route::post('memberships/{id}/purchase', [MembershipController::class, 'purchaseApi']);
});
