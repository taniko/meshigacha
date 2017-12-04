<?php

use Illuminate\Http\Request;
use App\Allergy;
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
        Route::group(['prefix' => 'foods'], function () {
            Route::post('/', 'FoodController@create');
            Route::get('/', 'FoodController@searchInRestaurant');
        });
    });
});

Route::group(['prefix' => 'foodstuffs'], function () {
    Route::post('/', 'FoodstuffController@create');
    Route::get('/', 'FoodstuffController@search');
    Route::group(['prefix' => '{foodstuff}'], function () {
        Route::get('/', 'FoodstuffController@find');
    });
});

Route::group(['prefix' => 'categories'], function () {
    Route::post('/', 'CategoryController@create');
    Route::get('/', 'CategoryController@search');
    Route::group(['prefix' => '{category}'], function () {
        Route::get('/', function (App\Category $category) {
            return $category;
        });
        Route::get('foods', 'CategoryController@foods');
    });
});

Route::group(['prefix' => 'allergies'], function () {
    Route::post('/', 'AllergyController@create');
    Route::get('/', 'AllergyController@search');
    Route::group(['prefix' => '{allergy}'], function () {
        Route::get('/', function (Allergy $allergy) {
            return $allergy;
        });
        Route::get('/foods', function (Allergy $allergy) {
            return $allergy->foods()->get();
        });
    });
});
