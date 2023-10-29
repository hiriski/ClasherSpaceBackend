<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BaseLayout\BaseLayoutController;
use App\Http\Controllers\BaseLayout\BaseLayoutTagController;
use App\Http\Controllers\BaseLayout\BaseLayoutCategoryController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Response::json([
        'message'   => 'Welcome to ' . config('app.name'),
        'status'    => 'OK'
    ], JsonResponse::HTTP_OK);
});

/**
 * -----------
 * Auth routes
 * -----------
 */
Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'registerWithEmailAndPassword']);
    Route::post('/login-check-username', [AuthController::class, 'checkUsername']);
    Route::post('/login', [AuthController::class, 'loginWithEmailAndPassword']);
    Route::post('/send-reset-password-link', [AuthController::class, 'sendResetPasswordLink']);
    Route::post('/password-reset/verify', [AuthController::class, 'verifyTokenPasswordReset']);
    Route::post('/password-reset', [AuthController::class, 'resetPassword']);
    Route::post('/revoke-token', [AuthController::class, 'revokeToken']);
    Route::get('/user', [AuthController::class, 'getAuthenticatedUser']);
});


/**
 * -----------
 * Base Layout
 * -----------
 */
Route::prefix('/base-layout')->group(function () {
    Route::apiResource('/category', BaseLayoutCategoryController::class);
    Route::apiResource('/tag', BaseLayoutTagController::class);
    Route::apiResource('/base', BaseLayoutController::class);
});

/**
 * -----------
 * Feedback
 * -----------
 */
Route::apiResource('/feedback', FeedbackController::class)->only(['store']);
