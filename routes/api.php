<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/user', [AuthController::class, 'user'])->name('user')->middleware('auth:sanctum');

Route::apiResource('teams', TeamController::class)->middleware('auth:sanctum');
Route::post('teams/{id?}/members', [TeamController::class, 'memberCreate'])->middleware(['auth:sanctum', 'team.owner']);
Route::delete('teams/{id?}/members/{user_id?}', [TeamController::class, 'memberDelete'])->middleware(['auth:sanctum', 'team.owner']);

Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');
Route::post('tasks/{id?}/files', [TaskController::class, 'files'])->middleware('auth:sanctum');