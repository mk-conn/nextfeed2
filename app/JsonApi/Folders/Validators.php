<?php

namespace App\JsonApi\Folders;


use App\JsonApi\DefaultValidator;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;

class Validators extends DefaultValidator
{

    protected $allowedIncludePaths = [
        'feeds'
    ];

    protected $allowedFilteringParameters = [];

    protected $allowedSortParameters = [
        'order',
        'name'
    ];

    /**
     * @var string
     */
    protected $resourceType = 'folders';


}
