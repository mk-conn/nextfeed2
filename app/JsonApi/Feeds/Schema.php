<?php

namespace App\JsonApi\Feeds;

use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'feeds';

    /**
     * @var array|null
     */
    protected $attributes = null;

}

