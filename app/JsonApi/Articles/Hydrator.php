<?php

namespace App\JsonApi\Articles;


use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

class Hydrator extends EloquentHydrator
{

    /**
     * @var array|null
     */
    protected $attributes = ['read', 'keep'];

    /**
     * @var array
     */
    protected $relationships = [
        //
    ];

}
