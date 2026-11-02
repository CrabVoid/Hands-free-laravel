<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');
Route::get('tags/search', [TagController::class, 'search'])->name('tags.search');
Route::resource('products', ProductController::class);
Route::put('products/{product}/tags', [ProductController::class, 'updateTags'])
    ->name('products.tags.update');
Route::patch('products/{product}/quantity', [ProductController::class, 'adjustQuantity'])
    ->name('products.quantity.adjust');
