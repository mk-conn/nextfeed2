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

JsonApi::register(
    'v1',
    [
        'namespace' => 'JsonApi',
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

Route::group(
    ['middleware' => 'auth:api'], function () {
    Route::get('api/v1/articles/{$id}/remote-content', 'Api\V1\ArticlesController@loadRemoteContent');
    Route::get('api/v1/articles/search', 'Api\V1\ArticlesController@search');
    Route::get('api/v1/feeds/discover', 'Api\V1\FeedsController@discover');
    Route::get('api/v1/feeds/{id}/mark-read', 'Api\V1\FeedsController@read');
    Route::get('api/v1/feeds/{id}/reload-icon', 'Api\V1\FeedsController@reloadIcon');
});
