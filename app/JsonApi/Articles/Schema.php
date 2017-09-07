<?php

namespace App\JsonApi\Articles;

use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * @var array|null
     */
    protected $attributes = null;

}

