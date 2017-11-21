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

Route::group(
    [
        'middleware' => 'api',
        'prefix'     => 'auth'
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('me', 'AuthController@me');
    }
);

Route::group(['prefix' => 'api'], function () {
//
//    Route::get('auth-user', 'AuthenticateController@getAuthUser');
//    Route::post('authenticate', 'AuthenticateController@authenticate');
//    Route::post('token-refresh', 'AuthenticateController@tokenRefresh');

    Route::get('article/scrape', 'ArticleController@scrapeContent')
         ->middleware('jwt.auth');

    Route::get('feeds/{id}/articles/mark-read', 'FeedsController@readAllArticles')
         ->middleware('jwt.auth');

    Route::get('feeds/discover/{url}', 'FeedsController@discover')
         ->middleware('jwt.auth');
});

JsonApi::register('v1', ['namespace' => 'Api', 'middleware' => 'auth:api'], function (ApiGroup $api) {
    $api->resource('feeds', ['has-many' => 'articles', 'has-one' => 'folder']);
    $api->resource('feed-actions');
    $api->resource('article-actions');
    $api->resource('folders', ['has-many' => 'feeds', 'has-one' => 'user']);
    $api->resource('articles', ['has-one' => 'feed']);
    $api->resource('settings');
    $api->resource('users', ['has-many' => ['folders', 'feeds', 'settings']]);
});
