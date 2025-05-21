<?php

use App\Http\Controllers\ActivityAreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\{
    RegisteredUserController,
    AuthenticatedSessionController,
    PasswordResetLinkController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
});

Route::apiResource('activity-area', ActivityAreaController::class);

Route::prefix('list')->group(function () {
    Route::get('activity-area', [ActivityAreaController::class, 'list']);
});
