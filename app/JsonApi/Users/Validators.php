<?php

namespace App\JsonApi\Users;

use App\JsonApi\DefaultValidator;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;

class Validators extends DefaultValidator
{
    
    /**
     * @var string
     */
    protected $resourceType = 'users';
    
    protected $allowedFilteringParameters = ['me'];
}
