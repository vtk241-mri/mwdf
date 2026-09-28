<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'getProducts')->name('get_products');
    Route::get('/products/{id}', 'getProductItem')->whereNumber('id')->name('get_product_item');
    Route::post('/products', 'createProduct')->name('post_products');
    Route::match(['put', 'patch'], '/products/{id}', 'updateProduct')->whereNumber('id')->name('put_products');
    Route::delete('/products/{id}', 'deleteProduct')->whereNumber('id')->name('delete_products');
});
