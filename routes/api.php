<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API - Sistem Inventaris
|--------------------------------------------------------------------------
| GET /api/products         -> daftar barang (paginated, ?q=keyword)
| GET /api/products/{id}    -> detail barang
*/
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
