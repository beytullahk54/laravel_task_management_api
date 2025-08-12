<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\LoginController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

Route::post('/login', [LoginController::class, 'login']);

Route::apiResource('teams', TeamController::class)->middleware('auth:sanctum');
Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');