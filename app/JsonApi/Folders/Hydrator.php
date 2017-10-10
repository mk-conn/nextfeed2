<?php

namespace App\JsonApi\Folders;


use CloudCreativity\LaravelJsonApi\Hydrator\EloquentHydrator;

class Hydrator extends EloquentHydrator
{

    /**
     * @var array|null
     */
    protected $attributes = ['name', 'open', 'order'];

    /**
     * @var array
     */
    protected $relationships = [
        'feeds',
        'user'
    ];

}
