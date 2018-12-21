<?php

namespace App\JsonApi\Folders;


use App\JsonApi\DefaultValidator;

class Validators extends DefaultValidator
{
    /**
     * @var string
     */
    protected $resourceType = 'folders';

    protected $allowedIncludePaths = [
        'feeds'
    ];

    protected $allowedFilteringParameters = [];

    protected $allowedSortParameters = [
        'order',
        'name'
    ];


}
