<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Laravel\Fortify\Http\Controllers\{
    RegisteredUserController,
    AuthenticatedSessionController,
    PasswordResetLinkController
};
use App\Http\Controllers\{ActivityAreaController,
    InstitutionController,
    GroupController,
    UserController,
    PlayerController
};

Route::get('/health-check', function () : Response {
    return response('brain-buddy-backend is on', 200);
});

Route::middleware(HandlePrecognitiveRequests::class)->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [RegisteredUserController::class, 'store']);
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('activity-area', ActivityAreaController::class);
    Route::apiResource('institution', InstitutionController::class);
    Route::apiResource('group', GroupController::class);
    Route::apiResource('player', PlayerController::class)->except('store');
    Route::get('group/report', [GroupController::class, 'report']);

    Route::prefix('list')->group(function () {
        Route::get('activity-area', [ActivityAreaController::class, 'list']);
        Route::get('institution', [InstitutionController::class, 'list']);
        Route::get('group', [GroupController::class, 'list']);
        Route::get('player', [PlayerController::class, 'list']);
        Route::get('user', [UserController::class, 'list']);
        Route::get('education-level', [GroupController::class, 'listEducationLevel']);
    });

    Route::post('user/{user}/institutions', [UserController::class, 'addToInstitution']);
    Route::delete('user/{user}/institution/{institution}', [UserController::class, 'removeFromInstitution']);
});

Route::middleware('app.token')->group(function () {
    Route::post('{groupAccessCode}/player', [PlayerController::class, 'store']);
});
