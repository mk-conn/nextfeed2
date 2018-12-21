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
//
//    Route::get('auth-user', 'AuthenticateController@getAuthUser');
//    Route::post('authenticate', 'AuthenticateController@authenticate');
//    Route::post('token-refresh', 'AuthenticateController@tokenRefresh');

    Route::get('article/scrape', 'ArticleController@scrapeContent')
         ->middleware('api.auth');

    Route::get('feeds/{id}/articles/mark-read', 'FeedsController@readAllArticles')
         ->middleware('api.auth');

    Route::get('feeds/discover/{url}', 'FeedsController@discover')
         ->middleware('api.auth');
});

Route::post('api/actions/feeds/{id}/read/{lastId}', 'Api\ActionsController@markFeedRead')
     ->middleware('auth:api');
Route::post('api/actions/articles/search', 'Api\ActionsController@searchArticles')
     ->middleware('auth:api');
Route::get('api/actions/articles/scrape/{articleId}', 'Api\ActionsController@scrapeArticle')
     ->middleware('auth:api');

JsonApi::register(
    'v1',
    [
        'namespace'  => 'JsonApi',
        'middleware' => 'json-api.auth:default'
    ],
    function (ApiGroup $api) {
        $api->resource('feeds', ['has-many' => 'articles', 'has-one' => 'folder']);
        $api->resource('feed-actions');
        $api->resource('article-actions');
        $api->resource('folders', ['has-many' => 'feeds', 'has-one' => 'user']);
        $api->resource('articles', ['has-one' => 'feed']);
        $api->resource('settings');
        $api->resource('users', ['has-many' => ['folders', 'feeds', 'settings']]);
    });
