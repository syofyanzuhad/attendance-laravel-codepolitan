<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function() {
        Route::post('logout', [AuthController::class, 'logout']);
        
        Route::post('password/reset', [PasswordResetController::class, 'reset']);
        Route::post('password/forgot', [PasswordResetController::class, 'sendResetLinkEmail']);
    });
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('attendance', [AttendanceController::class, 'store']);
    Route::get('attendance/history', [AttendanceController::class, 'history']);
});