<?php
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::get('/health-check', function () : Response
{
    return response('brain-buddy-backend is on', 200);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('activity-area', ActivityAreaController::class);
    Route::apiResource('institution', InstitutionController::class);
    Route::apiResource('group', GroupController::class);
    Route::apiResource('player', PlayerController::class);

    Route::prefix('list')->group(function () {
        Route::get('activity-area', [ActivityAreaController::class, 'list']);
        Route::get('institution', [InstitutionController::class, 'list']);
        Route::get('group', [GroupController::class, 'list']);
        Route::get('player', [PlayerController::class, 'list']);
    });

    Route::post('user/{user}/institutions', [UserController::class, 'addToInstitution']);
    Route::delete('user/{user}/institution/{institution}', [UserController::class, 'addToInstitution']);
});
