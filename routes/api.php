<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\ApiGroup;
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

//Route::middleware('auth:api')
//     ->get('/user', function (Request $request) {
//         return $request->user();
//     });


JsonApi::register('v1', ['namespace' => 'Api'], function (ApiGroup $api) {
    $api->resource('feeds');
    $api->resource('folders');
    $api->resource('articles');
    $api->resource('settings');
    $api->resource('users');
});
