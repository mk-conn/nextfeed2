<?php

namespace App\JsonApi\Users;


use App\JsonApi\DefaultSchema;

class Schema extends DefaultSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'users';

    /**
     * @var array|null
     */
    protected $attributes = ['username', 'fullname', 'email'];

}

