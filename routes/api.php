<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::post('/{order}/transition', [OrderController::class, 'transition']);
    Route::get('/{order}/events', [EventController::class, 'index']);
});

Route::prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
});
