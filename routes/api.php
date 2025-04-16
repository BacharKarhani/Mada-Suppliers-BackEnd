<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MarketController;
use App\Http\Controllers\API\SubmarketController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ContractorsOrSuppliersController;
use App\Http\Controllers\API\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin-only routes
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    // Market
    Route::post('/markets', [MarketController::class, 'store']);
    Route::put('/markets/{id}', [MarketController::class, 'update']);
    Route::delete('/markets/{id}', [MarketController::class, 'destroy']);
Route::get('submarkets/{id}', [SubmarketController::class, 'show']);

    // Submarket
    Route::post('/submarkets', [SubmarketController::class, 'store']);
    Route::put('/submarkets/{id}', [SubmarketController::class, 'update']);
    Route::delete('/submarkets/{id}', [SubmarketController::class, 'destroy']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/inactive', [UserController::class, 'inactiveUsers']);
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::patch('/users/{id}/accept', [UserController::class, 'accept']);
    Route::delete('/users/{id}/decline', [UserController::class, 'decline']);

    // Contractors or Suppliers (admin actions)
    Route::put('/contractors-or-suppliers/{id}', [ContractorsOrSuppliersController::class, 'update']);
    Route::delete('/contractors-or-suppliers/{id}', [ContractorsOrSuppliersController::class, 'destroy']);

    // Feedbacks (admin action)
    Route::delete('/feedbacks/{id}', [FeedbackController::class, 'destroy']);

});

// Regular authenticated user routes
Route::middleware('auth:sanctum')->group(function () {
    // ✅ User creates Market & Submarket (auto assign user_id from token)
    Route::post('/markets/user', [MarketController::class, 'storeByUser']);
    Route::post('/submarkets/user', [SubmarketController::class, 'storeByUser']);

    // Contractors or Suppliers
    Route::get('/contractors-or-suppliers', [ContractorsOrSuppliersController::class, 'index']);
    Route::post('/contractors-or-suppliers', [ContractorsOrSuppliersController::class, 'store']);

    Route::get('/contractors-or-suppliers/filter', [ContractorsOrSuppliersController::class, 'filter']);

    // Feedbacks
    Route::post('/feedbacks', [FeedbackController::class, 'store']);
    Route::get('/feedbacks/{id}', [FeedbackController::class, 'getByContractor']);

    Route::get('/submarkets', [SubmarketController::class, 'index']);
    Route::get('/markets', [MarketController::class, 'index']);
Route::get('/markets/all', [MarketController::class, 'allMarkets']);
Route::get('contractors-or-suppliers/{id}', [ContractorsOrSuppliersController::class, 'show']);
    Route::get('/export-contractors-or-suppliers', [ContractorsOrSuppliersController::class, 'export']);


    
});
Route::get('/test', [AuthController::class, 'test']);