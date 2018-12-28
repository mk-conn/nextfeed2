<?php

namespace App\JsonApi\Settings;

use App\JsonApi\DefaultValidator;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;

class Validators extends DefaultValidator
{
    
    /**
     * @var string
     */
    protected $resourceType = 'settings';
    
}
