<?php

namespace App\JsonApi\Feeds;


use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

/**
 * Class Hydrator
 *
 * @package App\JsonApi\Feeds
 */
class Hydrator extends EloquentHydrator
{

    /**
     * @var array|null
     */
    protected $attributes = [
        'name',
        'url',
        'icon',
        'feed-url',
        'site-url',
    ];

    /**
     * @var array
     */
    protected $relationships = [
        'folder',
        'articles'
    ];

}
