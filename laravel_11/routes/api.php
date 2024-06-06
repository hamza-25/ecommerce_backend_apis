<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store');
    Route::get('/categories/{id}','show');
    Route::put('/categories/{id}', 'update');
    Route::delete('/categories/{id}', 'destroy');
});


Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::post('/products', 'store');
    Route::get('/products/{id}', 'show');
    Route::put('/products/{id}',  'update');
    Route::delete('/products/{id}',  'destroy');
});


Route::controller(AddressController::class)->group(function () {
    Route::get('/addresses', 'index');
    Route::post('/addresses', 'store');
    Route::get('/addresses/{id}', 'show');
    Route::put('/addresses/{id}',  'update');
    Route::delete('/addresses/{id}',  'destroy');
});


Route::controller(CategoryController::class)->group(function () {
    Route::get('/orders', 'index');
    Route::post('/orders', 'store');
    Route::get('/orders/{id}', 'show');
    Route::put('/orders/{id}',  'update');
    Route::delete('/orders/{id}',  'destroy');
});
