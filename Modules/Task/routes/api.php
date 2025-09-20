<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\TaskController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tasks', TaskController::class)->names('task');
});



Route::middleware(['auth:sanctum'])->group(function () {

    // Routes accessible by all authenticated users
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);

    // Routes only for Managers
    Route::middleware(['role:Manager'])->group(function () {
        Route::post('tasks', [TaskController::class, 'store']);
        Route::post('tasks/{id}/dependencies', [TaskController::class,'addDependencies']);
    });

    // Routes for Managers OR Users
    Route::middleware(['role:Manager|User'])->group(function () {
        Route::put('tasks/{id}', [TaskController::class, 'update']);

    });
});
