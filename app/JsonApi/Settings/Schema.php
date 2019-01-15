<?php

namespace App\JsonApi\Settings;

use App\JsonApi\DefaultSchema;

class Schema extends DefaultSchema
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

