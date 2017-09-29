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

Route::group(['prefix' => 'api'], function () {
    Route::get('auth-user', 'AuthenticateController@getAuthUser');
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::post('token-refresh', 'AuthenticateController@tokenRefresh');
    Route::get('article/scrape', 'ArticleController@scrapeContent')
         ->middleware('jwt.auth');
    Route::get('feeds/refresh/{id}?', 'FeedsController@refresh')
         ->middleware('jwt.auth');
    Route::get('feeds/{id}/articles/mark-read', 'FeedsController@readAllArticles')
         ->middleware('jwt.auth');
});

JsonApi::register('v1', ['namespace' => 'Api', 'middleware' => 'jwt.auth'], function (ApiGroup $api) {
    $api->resource('feeds', ['has-many' => 'articles', 'has-one' => 'folder']);
    $api->resource('folders', ['has-many' => 'feeds', 'has-one' => 'user']);
    $api->resource('articles');
    $api->resource('settings');
    $api->resource('users', ['has-many' => ['folders', 'feeds', 'settings']]);
});
