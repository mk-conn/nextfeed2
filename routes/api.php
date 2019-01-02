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
Route::get('api/v1/feeds/{id}/mark-read', 'Api\V1\FeedsController@read')
     ->middleware('auth:api');
Route::get('api/v1/feeds/discover', 'Api\V1\FeedsController@discover')
     ->middleware('auth:api');
Route::get('api/v1/articles/search', 'Api\V1\ArticlesController@search')
     ->middleware('auth:api');
//Route::get('api/actions/articles/scrape/{articleId}', 'Api\ActionsController@scrapeArticle')
//     ->middleware('auth:api');

JsonApi::register(
    'v1',
    [
        'namespace'  => 'JsonApi',
        //'middleware' => 'json-api.auth:default'
    ],
    function (ApiGroup $api) {
        Route::group(
            ['middleware' => 'json-api.auth:default'], function () use ($api) {
            $api->resource('feeds', ['has-many' => 'articles', 'has-one' => 'folder']);
            $api->resource('feed-actions');
            $api->resource('article-actions');
            $api->resource('folders', ['has-many' => 'feeds', 'has-one' => 'user']);
            $api->resource('articles', ['has-one' => 'feed']);
            $api->resource('settings');
        });
        $api->resource(
            'users', [
            'has-many' => ['folders', 'feeds', 'settings']
        ]);
    });
