<?php

namespace App\JsonApi\Folders;

use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'folders';

    /**
     * @var array|null
     */
    protected $attributes = null;

}

