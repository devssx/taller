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
    // Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/', 'SalesController@create')->name('sales'); // Default

    Route::middleware(['checkrole'])->prefix('cars')->group(function () {
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
        Route::get('/receipt/{id?}', 'SalesController@receipt')->name('sales.receipt');
        Route::get('/copy/{id?}', 'SalesController@copy')->name('sales.copy');
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', 'ClientsController@index')->name('clients.list');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', 'UsersController@index')->name('users.list');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', 'RolesController@index')->name('roles.list');
    });

    Route::prefix('carservices')->group(function () {
        Route::get('/', 'CarServicesController@index')->name('carservices.list');
        Route::get('/create', 'CarServicesController@create')->name('carservices.create');
        Route::get('/edit/{id}', 'CarServicesController@edit')->name('carservices.edit');
    });

    // Bitacora
    Route::prefix('bitacora')->group(function () {
        Route::get('/cars', 'SalesController@bitacoraCars')->name('bitacora.cars');
        Route::get('/cleaning', 'CleaningController@bitacoraCleanings')->name('bitacora.cleaning');
    });

    // Nomina
    Route::get('/payroll', 'PayrollController@index')->name('payroll');

    // Gastos / Ingresos
    Route::get('/expenses', 'SalesController@expenses')->name('expenses');

    // Reportes
    Route::get('/reports', 'GuaranteeController@reports')->name('reports');
    Route::get('/guarantee', 'GuaranteeController@guarantee')->name('guarantee');

    // Tools
    Route::post('files/uploadItem', 'FileController@uploadItem')->name('upload.item');
    Route::post('files/uploadCar', 'FileController@uploadCar')->name('upload.car');
    Route::apiResource('/app', 'AppController');
    
    Route::get('/workshops', function () {
        return view('workshops.index');
    })->name('workshops.list');
});
