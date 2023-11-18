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

    Route::get('/user/me',[UserController::class,"me"])
        ->can("viewMe,App\Models\User");
        ;
    Route::get("user{login?}",[UserController::class,"getUserByLogin"])
        ->can("viewLogin,App\Models\User,login");



    Route::get("/role",[RoleController::class,"index"])
        ->can("viewAny,App\Models\Role");


    Route::get("/pre-registration/",[PreRegistrationController::class,"index"])
        ->can("viewAny,App\Models\PreRegistration");
    Route::get("/pre-registration/my/",[PreRegistrationController::class,"indexMy"])
        ->can("viewMy,App\Models\PreRegistration");

    Route::post("/pre-registration/",[PreRegistrationController::class,"store"])
        ->can("create,App\Models\PreRegistration")
        ->name("pre-registration.store");
    Route::patch("/pre-registration/{preRegistration}",[PreRegistrationController::class,"acceptOrReject"])
        ->can("update,App\Models\PreRegistration,preRegistration");

    Route::apiResource("laboratory",\App\Http\Controllers\Api\LaboratoryController::class);


});
