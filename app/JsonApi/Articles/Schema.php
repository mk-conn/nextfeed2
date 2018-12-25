<?php

namespace App\JsonApi\Articles;


use App\JsonApi\DefaultSchema;
use App\Models\Article;

/**
 * Class Schema
 *
 * @package App\JsonApi\Articles
 */
class Schema extends DefaultSchema
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
                self::SHOW_DATA    => isset($includeRelationships['feed']),
                self::DATA         => function () use ($resource) {
                    return $resource->feed;
                }
            ]
        ];
    }
    
    public function getPrimaryMeta($resource)
    {
        return [
            'audits' => count($resource->audits)
        ];
    }
    
}

