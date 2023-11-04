<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login',[\App\Http\Controllers\Api\UserController::class,"loginUser"]);

Route::middleware('auth:sanctum')->group(function (){

    Route::get('/user/me',[\App\Http\Controllers\Api\UserController::class,"me"]);
    Route::get("user{login?}",[\App\Http\Controllers\Api\UserController::class,"getUserByLogin"]);

    Route::get("/pre-registration/",[\App\Http\Controllers\Api\PreRegistrationController::class,"index"]);
    Route::post("/pre-registration/store/",[\App\Http\Controllers\Api\PreRegistrationController::class,"store"]);

});
