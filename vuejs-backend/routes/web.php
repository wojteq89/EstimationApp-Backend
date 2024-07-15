<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ResetPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true, 'reset' => true]);

Route::prefix('api')->group(function () {
    
    Route::prefix('clients')->group(function () {
        Route::middleware(['auth:api', 'check.role:admin,user'])->group(function () {
            Route::get('/', [ClientController::class, 'index']);
            Route::get('/{id}', [ClientController::class, 'show']);
        });
        
        Route::middleware(['auth:api', 'check.role:admin'])->group(function () {
            Route::post('/', [ClientController::class, 'store']);
            Route::put('/{id}', [ClientController::class, 'update']);
            Route::delete('/{id}', [ClientController::class, 'destroy']);
        });
    });

    Route::prefix('projects')->group(function () {
        Route::middleware(['auth:api', 'check.role:admin,user'])->group(function () {
            Route::get('/', [ProjectController::class, 'index']);
            Route::get('/{id}', [ProjectController::class, 'show']);
        });

        Route::middleware(['auth:api', 'check.role:admin'])->group(function () {
            Route::post('/', [ProjectController::class, 'store']);
            Route::put('/{id}', [ProjectController::class, 'update']);
            Route::delete('/{id}', [ProjectController::class, 'destroy']);
        });
    });

    Route::prefix('estimations')->group(function () {
        Route::middleware(['auth:api', 'check.role:admin,user'])->group(function () {
            Route::get('/', [EstimationController::class, 'index']);
            Route::get('/{id}', [EstimationController::class, 'show']);
        });

        Route::middleware(['auth:api', 'check.role:admin'])->group(function () {
            Route::post('/', [EstimationController::class, 'store']);
            Route::put('/{id}', [EstimationController::class, 'update']);
            Route::delete('/{id}', [EstimationController::class, 'destroy']);
        });
    });

    Route::prefix('users')->group(function () {
        Route::middleware(['auth:api', 'check.role:admin'])->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });
    });

        Route::post('/reset-request', [ResetPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
            ->name('password.update');
        
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
