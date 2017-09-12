<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\ApiGroup;

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

//Route::middleware('auth:api')
//     ->get('/user', function (Request $request) {
//         return $request->user();
//     });


Route::group(['prefix' => 'api'], function () {
    Route::get('auth-user', 'AuthenticateController@getAuthUser');
    Route::post('authenticate', 'AuthenticateController@authenticate');
});

JsonApi::register('v1', ['namespace' => 'Api', 'middleware' => 'jwt.auth'], function (ApiGroup $api) {
    $api->resource('feeds');
    $api->resource('folders');
    $api->resource('articles');
    $api->resource('settings');
    $api->resource('users');
});
