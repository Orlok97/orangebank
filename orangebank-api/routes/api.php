<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\ContaCorrenteController;
use App\HTTP\Controllers\ExtratoCorrenteController;
use App\HTTP\Controllers\ContaInvestimentoController;
use App\HTTP\Controllers\ExtratoInvestimentoController;

Route::get('/users',[UserController::class,'index']);
Route::post('/user',[UserController::class,'create']);
Route::post('/auth',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/user/auth',[UserController::class,'getAuthUser'])->middleware('auth:sanctum');

Route::prefix('corrente')->middleware('auth:sanctum')->group(function(){
    Route::get('/saldo',[ContaCorrenteController::class,'getSaldo']);
    Route::post('/',[ContaCorrenteController::class,'depositarSaldo']);
    Route::post('/transferir/{id}',[ContaCorrenteController::class,'transferirSaldo']);
    Route::get('/extrato',[ExtratoCorrenteController::class,'listExtrato']);
});

Route::prefix('investimento')->middleware('auth:sanctum')->group(function(){
    Route::get('/saldo',[ContaInvestimentoController::class,'getSaldo']);
    Route::post('/',[ContaInvestimentoController::class,'depositarSaldo']);
    Route::post('/comprar',[ContaInvestimentoController::class,'comprarAtivos']);
    Route::get('/extrato',[ExtratoInvestimentoController::class,'listExtrato']);
});