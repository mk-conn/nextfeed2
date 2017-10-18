<?php

namespace App\JsonApi\ArticleActions;

use App\Models\ArticleAction;
use CloudCreativity\LaravelJsonApi\Schema\AbstractSchema;
use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends AbstractSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'article-actions';

    /**
     * @param object $resource
     *
     * @return mixed
     */
    public function getId($resource)
    {
        return 'result';
    }

    /**
     * @param ArticleAction $resource
     *
     * @return mixed
     */
    public function getAttributes($resource)
    {
        return [
            'result' => $resource->result
        ];
    }
}


