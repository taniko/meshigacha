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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'restaurants'], function () {
    Route::post('/', 'RestaurantController@create');
    Route::get('/', 'RestaurantController@index');
    Route::group(['prefix' => '{restaurant}'], function () {
        Route::get('/', 'RestaurantController@find');
    });
});

Route::group(['prefix' => 'foodstuffs'], function () {
    Route::post('/', 'FoodstuffController@create');
    Route::get('/', 'FoodstuffController@search');
    Route::group(['prefix' => '{foodstuff}'], function () {
        Route::get('/', 'FoodstuffController@find');
    });
});
