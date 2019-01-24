<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'SalesController@index');

    Route::prefix('cars')->group(function () {
        Route::get('/', 'CarsController@index')->name('cars.list');
        Route::get('/create', 'CarsController@create')->name('cars.create');
    });

    Route::prefix('services')->group(function () {
        Route::get('/', 'ServicesController@index')->name('services.list');
        Route::get('/create', 'ServicesController@create')->name('services.create');
    });

    Route::prefix('items')->group(function () {
        Route::get('/', 'ItemsController@index')->name('items.list');
        Route::get('/create', 'ItemsController@create')->name('items.create');
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', 'SalesController@index')->name('sales.list');
        Route::get('/create', 'SalesController@create')->name('sales.create');
        Route::get('/receipt', 'SalesController@receipt')->name('sales.receipt');
    });
});
