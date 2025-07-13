<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\ContaCorrenteController;

Route::get('/users',[UserController::class,'index']);
Route::post('/user',[UserController::class,'create']);
Route::post('/auth',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/user/auth',[UserController::class,'getAuthUser'])->middleware('auth:sanctum');

Route::prefix('corrente')->middleware('auth:sanctum')->group(function(){
    Route::get('/',[ContaCorrenteController::class,'getSaldo']);
    Route::post('/',[ContaCorrenteController::class,'depositarSaldo']);
    Route::post('/transferir/{id}',[ContaCorrenteController::class,'transferirSaldo']);
});