<?php

use App\Http\Controllers\Api\ActivityTypeController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\HistoricalRateController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleChangeRequestController;

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('users',[UserController::class,'index']);

    Route::post('change_role',[UserController::class,'requestChangeRole']);

    Route::prefix('role-change-requests')
        ->controller(RoleChangeRequestController::class)
        ->group(function(){
            Route::get('/', 'index');
            Route::post('/{id}/approve', 'approve');
            Route::post('/{id}/reject', 'reject');
    });

    Route::prefix('roles')->group(function(){
        Route::post('accept/{id}', [RoleController::class, 'roleChange']);
    });

    // Company Routes
    Route::prefix('companies')->group(function(){
        Route::apiResource('/', CompanyController::class);
        Route::post('/{company}/associate-activity', [CompanyController::class, 'associateActivity']);
        Route::post('/{company}/dissociate-activity', [CompanyController::class, 'dissociateActivity']);
    });

    // Activity Types Routes
    Route::apiResource('activity-types', ActivityTypeController::class);
    
    Route::post('convert', [CurrencyController::class, 'convert']);

    Route::get('historical-rates', [HistoricalRateController::class, 'index']);

});