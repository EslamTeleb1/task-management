<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class)->names('user');
});


// to check the current authenticated user

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
