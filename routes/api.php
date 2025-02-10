<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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
