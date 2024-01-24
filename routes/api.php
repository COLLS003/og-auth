<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Account endpoints

// public routes
Route::post('/users/register', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login'])->name('login');
Route::get('/forbiden', [AuthController::class, 'forbiden'])->name('forbiden');

// google auth api endpoints

Route::get('/google/login/url', [GoogleController::class, 'getAuthUrl']);
Route::post('google/auth/login', [GoogleController::class, 'postLogin']);

// Protected routes ..
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Protected routes go here
    Route::get('/accounts/list', [AccountsController::class, 'list']);
    Route::get('/accounts/read/{id}', [AccountsController::class, 'find']);
    Route::post('/accounts/create', [AccountsController::class, 'create']); // Corrected typo
    Route::put('/accounts/update/{id}', [AccountsController::class, 'update']);
    Route::delete('/accounts/delete/{id}', [AccountsController::class, 'delete']);
    //users endpoint
    Route::post('/users/logout', [AuthController::class, 'logout']);
});



    ?>

