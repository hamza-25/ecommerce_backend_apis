<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\isAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories/{id}', 'show');
    Route::get('/categories', 'index');
    Route::get('/categories/{id}/products', 'get_products_by_category');
    // Route::get('/categories/products', function (){
    //     return 'hello';
    // });
});


Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
});


Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/login', 'login');
    Route::post('/auth/register', 'register');
});

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');


Route::middleware(['auth:api'])->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::controller(AddressController::class)->group(function () {
        Route::get('/addresses', 'index'); // check request user id == addresses.user.id
        Route::post('/addresses', 'store');
        Route::get('/addresses/{id}', 'show');
        Route::put('/addresses/{id}',  'update');
        Route::delete('/addresses/{id}',  'destroy');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders/{id}', 'show'); // check request user id == addresses.user.id
        Route::post('/orders', 'store');
    });


    Route::controller(CartController::class)->group(function () {
        Route::get('/cart/clear', 'clear');
        Route::get('/cart', 'index');
        Route::post('/cart', 'store');
        Route::delete('/cart/{id}', 'destroy');
    });
});


Route::middleware(['auth:api', isAdmin::class])->group(function () {

    Route::controller(CategoryController::class)->group(function () {
        Route::post('/categories', 'store');
        Route::put('/categories/{id}', 'update');
        Route::delete('/categories/{id}', 'destroy');
    });
    
    Route::controller(ProductController::class)->group(function () {
        Route::post('/products', 'store');
        Route::put('/products/{id}',  'update');
        Route::delete('/products/{id}',  'destroy');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::put('/orders/{id}',  'update');
        Route::delete('/orders/{id}',  'destroy');
    });
});
