<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\AuthController;

Route::get('/users',[UserController::class,'index']);
Route::post('/user',[UserController::class,'create']);

Route::post('/auth',[AuthController::class,'login']);