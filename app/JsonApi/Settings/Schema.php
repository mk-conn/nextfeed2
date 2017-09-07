<?php

namespace App\JsonApi\Settings;

use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'settings';

    /**
     * @var array|null
     */
    protected $attributes = null;

}

