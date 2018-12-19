<?php

namespace App\JsonApi\Articles;


use App\JsonApi\DefaultValidator;

class Validators extends DefaultValidator
{

    protected $allowedSortParameters = [
        'publish-date',
        'title',
        'author',
        'updated-date',
        'id'
    ];

    protected $allowedFieldSetTypes = null;

    protected $allowedFilteringParameters = [
        'feed',
        'keep',
        'read'
    ];

    protected $allowedIncludePaths = ['feed'];

    /**
     * @var string
     */
    protected $resourceType = 'articles';

}
