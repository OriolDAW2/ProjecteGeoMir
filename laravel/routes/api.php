<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\TokenController;



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

Route::get('user', [TokenController::class, 'user'])
->middleware(['auth:sanctum']);

Route::post('register', [TokenController::class, 'register']);

Route::post('login', [TokenController::class, 'login']);

Route::post('logout', [TokenController::class, 'logout'])
->middleware(['auth:sanctum']);

Route::apiResource('files', FileController::class);

Route::post('files/{file}', [FileController::class, 'update_workaround']);

