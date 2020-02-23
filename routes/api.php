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

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

/*Admin auth*/
Route::prefix('admin')->group(function() {
    Route::post('login', 'PassportController@adminLogin');
    Route::post('register', 'PassportController@adminRegister');
});

Route::middleware(['checkIp', 'auth:api', 'throttle:60,1'])->group(function () {
    Route::get('user', 'PassportController@details');
    Route::get('user/{id}', 'PassportController@getDetailsById');
    Route::post('user/details', 'PassportController@getDetailsByEmail');
    Route::put('user', 'PassportController@updateUser');

    Route::resource('products', 'ProductController');
});

//Admin routes
Route::middleware(['checkIp', 'auth:api', 'throttle:60,1', 'scope:get,create,update,delete'])->group(function () {
    Route::get('admin/category', 'CategoryController@index');
    Route::get('admin/category/{id}', 'CategoryController@show');
    Route::post('admin/category', 'CategoryController@store');
    Route::post('admin/category/child', 'CategoryController@storeAChildNode');
    Route::delete('admin/category', 'CategoryController@destroy');
    Route::delete('admin/category/parent', 'CategoryController@destroyParentWithoutChildren');
});

/*Route::middleware(['checkIp', 'auth:api'], 'throttle:3,1')->get('/user', function (Request $request) {
    return $request->user();
});*/
