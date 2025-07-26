<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordCheckController;
use App\Http\Controllers\UserRolesController;
use App\Http\Controllers\ProcessingResultController;
use App\Http\Controllers\Api\ApiWarehouseMaterialMovementController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\ChatGPTController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ExpenseClassifierController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Protect this route with the auth:sanctum middleware
Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);

Route::middleware(App\Http\Middleware\CheckApiKey::class)->post('/check-password', [PasswordCheckController::class, 'checkPassword']);
Route::middleware(App\Http\Middleware\CheckApiKey::class)->post('/get-user-roles', [UserRolesController::class, 'getUserRoles']);
Route::middleware(App\Http\Middleware\CheckApiKey::class)->post('/user-list', [App\Http\Controllers\Api\UserController::class, 'getAllUsers']);
Route::middleware(App\Http\Middleware\CheckApiKey::class)->post('/get-positions', [PositionController::class, 'getAllPositions']);
Route::middleware(App\Http\Middleware\CheckApiKey::class)->post('/get-departments', [DepartmentController::class, 'getAllDepartments']);

Route::middleware([App\Http\Middleware\CheckApiKey2::class])->group(function () {
    Route::get('/order-details', [OrderDetailController::class, 'index']);
    Route::post('warehouse', [ApiWarehouseMaterialMovementController::class, 'store']);
    Route::post('warehouses-list', [WarehouseController::class, 'getAllWarehouses']);
    Route::post('history', [StockHistoryController::class, 'forApi']);
    Route::apiResource('expense-classifiers', ExpenseClassifierController::class);
    Route::get('/processing-status', [ProcessingResultController::class, 'show']);
});

Route::middleware([App\Http\Middleware\CheckApiKey3::class])->group(function () {
    Route::post('/ChatGPT', [ChatGPTController::class, 'store']);
});
