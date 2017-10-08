<?php

namespace App\JsonApi\FeedActions;

use CloudCreativity\LaravelJsonApi\Schema\AbstractSchema;

class Schema extends AbstractSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'feed-actions';

    /**
     * @param object $resource
     * @return mixed
     */
    public function getId($resource)
    {
        // TODO
    }

    /**
     * @param object $resource
     * @return mixed
     */
    public function getAttributes($resource)
    {
        return [

        ];
    }
}
