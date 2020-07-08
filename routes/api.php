<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/car/search', 'CarsController@searchCar');
Route::get('/car/brands', 'CarsController@listBrands');
Route::get('/car/brandsByMaker', 'CarsController@getBrandsOf');
Route::get('/car/makers', 'CarsController@listMakers');
Route::get('/servicesByCar', 'ServicesController@getByCar');
Route::get('/servicesOfCar', 'ServicesController@getServicesByCarID');

Route::get('/clients', 'ClientsController@get');
Route::post('/clients', 'ClientsController@save');
Route::delete('/clients/{id}', 'ClientsController@delete');

Route::get('/sales/searchByDay', 'SalesController@searchByDay');
Route::get('/sales/searchByWeek', 'SalesController@searchByWeek');
Route::get('/sales/searchReceiptByWeek', 'SalesController@searchReceiptByWeek');
Route::get('/sales', 'SalesController@get');
Route::post('/sales', 'SalesController@save');
Route::post('/sales/status', 'SalesController@changeStatus');

Route::get('/items', 'ItemsController@get');
Route::post('/items', 'ItemsController@save');
Route::post('/updateItem', 'ItemsController@update');
Route::delete('/items/{id}', 'ItemsController@delete');

Route::get('/cars', 'CarsController@get');
Route::post('/cars', 'CarsController@save');
Route::delete('/cars/{id}', 'CarsController@delete');

Route::get('/services', 'ServicesController@get');
Route::post('/services', 'ServicesController@save');
Route::delete('/services/{id}', 'ServicesController@delete');

Route::get('/carservices', 'CarServicesController@get');
Route::post('/carservices', 'CarServicesController@save');
Route::delete('/carservices/{id}', 'CarServicesController@delete');

Route::get('/users', 'UsersController@get');
Route::post('/users', 'UsersController@save');
Route::delete('/users/{id}', 'UsersController@delete');

Route::get('/roles', 'RolesController@get');
Route::post('/roles', 'RolesController@save');
Route::delete('/roles/{id}', 'RolesController@delete');

Route::get('/dashboard', 'DashboardController@get');


Route::get('/cleaning', 'CleaningController@get');
Route::get('/cleaning/search', 'CleaningController@search');
Route::get('/cleaning/searchWeek', 'CleaningController@searchWeek');
Route::post('/cleaning', 'CleaningController@save');
Route::delete('/cleaning/{id}', 'CleaningController@delete');

Route::post('/payroll/comment', 'PayrollController@saveComment');
Route::post('/payroll/save', 'PayrollController@saveWeek');
Route::get('/payroll/comment', 'PayrollController@getComment');
Route::get('/payroll', 'PayrollController@getPayroll');
Route::get('/payroll/userComments', 'PayrollCommentController@getUserComments');
Route::get('/workshop', 'WorkShopController@index');