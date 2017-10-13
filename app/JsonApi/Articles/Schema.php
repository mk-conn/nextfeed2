<?php

namespace App\JsonApi\Articles;


use App\Models\Article;
use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

/**
 * Class Schema
 *
 * @package App\JsonApi\Articles
 */
class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * @var array|null
     */
    protected $attributes = [
        'title',
        'author',
        'description',
        'language',
        'publish_date',
        'updated_date',
        'content',
        'description',
        'url',
        'categories',
        'read',
        'keep',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @param Article $resource
     * @param bool    $isPrimary
     * @param array   $includeRelationships
     *
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'feed' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => isset($includeRelationships['feed']) ? $resource->feed :
                    $this->createBelongsToIdentity($resource, 'feed')
            ]
        ];

        $data = array_merge($included, [
            'feed' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => isset($includeRelationships['feed']) ? $resource->feed :
                    $this->createBelongsToIdentity($resource, 'feed')
            ]
        ]);

        return $data;
    }
}

