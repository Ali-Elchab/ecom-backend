<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/insert_product', 'ProductsController@insert_product');
Route::post('/update_product', 'ProductsController@update_product');
Route::get('/products', 'ProductsController@get_products');

Route::post('/create_order', [OrderController::class, 'create_order']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
