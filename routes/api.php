<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('v1')->group(function () {

    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);



    Route::group(['middleware' => ['auth:sanctum']], function () {
        
        Route::apiResource('users', UserController::class);
        Route::post('/logout',[AuthController::class,'logout']);
        Route::apiResource('roles', RoleController::class);
        
        Route::apiResource('projects', ProjectController::class);
        Route::post('assign-project', [ProjectController::class,'assignProject']);
        Route::apiResource('tasks', TaskController::class);
    });
     


});
