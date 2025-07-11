<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\UserController;

Route::get('/users',[UserController::class,'index']);
Route::post('/user',[UserController::class,'create']);