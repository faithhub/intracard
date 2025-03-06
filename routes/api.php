<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

use App\Http\Controllers\VeriffController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware(['api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class])
//     ->group(function () {
//     Route::prefix('auth')->group(function () {
//         Route::post('/sign-in', [AuthController::class, 'login'])->name('api.auth.login');
//         Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
//         Route::get('/status', [AuthController::class, 'status'])->name('api.auth.status'); // To check auth status
//     });
// });

// Route::middleware([StartSession::class])->group(function () {

// Route::post('/veriff/start', [VeriffController::class, 'createSession']);
Route::post('/veriff/webhook', [VeriffController::class, 'handleWebhook']);
// Route::get('/veriff/status', [VeriffController::class, 'checkSessionDecision']);

// });