<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;


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

Route::get('/api/clients', [ClientController::class, 'index']);
Route::get('/api/clients/{id}', [ClientController::class, 'show']);
Route::post('/api/clients', [ClientController::class, 'store']);
Route::put('/api/clients/{id}', [ClientController::class, 'update']);
Route::delete('/api/clients/{id}', [ClientController::class, 'destroy']);

Route::get('/api/projects', [ProjectController::class, 'index']);
Route::get('/api/projects/{id}', [ProjectController::class, 'show']);
Route::post('/api/projects', [ProjectController::class, 'store']);
Route::put('/api/projects/{id}', [ProjectController::class, 'update']);
Route::delete('/api/projects/{id}', [ProjectController::class, 'destroy']);

Route::get('/api/estimations', [EstimationController::class, 'index']);
Route::get('/api/estimations/{id}', [EstimationController::class, 'show']);
Route::post('/api/estimations', [EstimationController::class, 'store']);
Route::put('/api/estimations/{id}', [EstimationController::class, 'update']);
Route::delete('/api/estimations/{id}', [EstimationController::class, 'destroy']);

Route::get('/api/users', [UserController::class, 'index']);
Route::get('/api/users/{id}', [UserController::class, 'show']);
Route::post('/api/users', [UserController::class, 'store']);
Route::put('/api/users/{id}', [UserController::class, 'update']);
Route::delete('/api/users/{id}', [UserController::class, 'destroy']);

Route::post('/api/register', [AuthController::class, 'register']);
Route::post('/api/login', [AuthController::class, 'login']);

Route::post('/api/password/reset-request', 'App\Http\Controllers\PasswordResetController@sendResetLinkEmail')->name('password.email');
Route::post('/api/password/reset', 'App\Http\Controllers\PasswordResetController@reset')->name('password.update');
