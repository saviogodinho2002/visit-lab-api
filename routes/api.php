<?php

use App\Http\Controllers\Api\PreRegistrationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
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

Route::post('login',[UserController::class,"loginUser"]);

Route::middleware('auth:sanctum')->group(function (){

    Route::get('/user/me',[UserController::class,"me"]);
    Route::get("user{login?}",[UserController::class,"getUserByLogin"]);
    Route::get("/role",[RoleController::class,"index"]);

    Route::get("/pre-registration/",[PreRegistrationController::class,"index"]);
    Route::post("/pre-registration/store/",[PreRegistrationController::class,"store"])
        ->can("create,App\Models\PreRegistration");

});
