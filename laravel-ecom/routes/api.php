<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::post('/insert_product', [ProductsController::class, 'insert_product']);
Route::post('/update_product', [ProductsController::class, 'update_product']);
Route::get('/get_products', [ProductsController::class, 'get_products']);

Route::post('/orders/create_order', [OrderController::class, 'create_order']);
Route::post('/orders/{orderId}/add_product_to_order', [OrderController::class, 'add_product_to_order']);
Route::post('/orders/{orderId}/edit_order', [OrderController::class, 'edit_order']);
Route::get('/orders/{orderId}/get_order', [OrderController::class, 'get_order']);
Route::post('/orders/{orderId}/delete_order', [OrderController::class, 'delete_order']);

Route::post('/shoppingCart/{userId}/addToCart', [ShoppingCartController::class, 'addToCart']);
Route::post('/shoppingCart/{userId}/removeFromCart', [ShoppingCartController::class, 'removeFromCart']);
Route::post('/shoppingCart/{userId}/viewCart', [ShoppingCartController::class, 'viewCart']);
