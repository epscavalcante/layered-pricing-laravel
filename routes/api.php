<?php

use App\Http\Controllers\LayerController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)
    ->prefix('/products')
    ->name('products.')
    ->group(function () {
        Route::get('/{product_id}', 'show')->name('show');
        Route::post('/simple', 'storeSimple')->name('store_simple');
    });

Route::controller(LayerController::class)
    ->prefix('/layers')
    ->name('layers.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{layer_id}', 'show')->name('show');
        Route::get('/{layer_id}/prices', 'listPrices')->name('list_prices');
        Route::post('/', 'store')->name('store');
    });

Route::controller(PriceController::class)
    ->prefix('/prices')
    ->name('prices.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/simulate', 'simulate')->name('simulate');
        Route::get('/{price_id}', 'show')->name('show');
        Route::post('/simple', 'storeSimple')->name('store_simple');
        Route::post('/discount', 'storeDiscount')->name('store_discount');
    });
