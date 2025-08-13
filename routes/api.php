<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\LoginController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::apiResource('teams', TeamController::class)->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');
Route::post('tasks/{id?}/files', [TaskController::class, 'files'])->middleware('auth:sanctum');