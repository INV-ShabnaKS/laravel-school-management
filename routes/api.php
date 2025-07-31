<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/teachers', [TeacherController::class, 'store']);

