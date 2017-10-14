<?php

namespace App\JsonApi\Feeds;


use App\Models\Feed;
use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

/**
 * Class Schema
 *
 * @package App\JsonApi\Feeds
 */
class Schema extends EloquentSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'feeds';

    /**
     * @var array|null
     */
    protected $attributes = [
        'name',
        'url',
        'description',
        'feed_url',
        'site_url',
        'logo',
        'icon',
        'order',
        'settings',
        'update_error',
        'created_at',
        'updated_at'
    ];

    /**
     * @param Feed  $resource
     * @param bool  $isPrimary
     * @param array $includeRelationships
     *
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        $includes = [];

        if (isset($includeRelationships['articles'])) {
            $includes['articles'] = [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => $resource->articles
            ];
        }

        return array_merge($includes, [
            'folder' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => isset($includeRelationships['folder']) ?
                    $resource->folder :
                    $this->createBelongsToIdentity($resource, 'folder')
            ],
            'user'   => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => isset($includeRelationships['user']) ?
                    $resource->user :
                    $this->createBelongsToIdentity($resource, 'user')
            ]
        ]);
    }

    /**
     * @param Feed $resource
     *
     * @return array*
     */
    public function getPrimaryMeta($resource)
    {
        return [
            'articles-count'        => $resource->articles->count(),
            'articles-unread-count' => $resource->articles->where('read', false)
                                                          ->count(),
            'articles-starred'      => $resource->articles->where('keep', true)
                                                          ->count()
        ];
    }

    /**
     * @param object $resource
     *
     * @return array
     */
    public function getInclusionMeta($resource)
    {
        return [
            'articles-count'        => $resource->articles->count(),
            'articles-unread-count' => $resource->articles->where('read', false)
                                                          ->count(),
            'articles-starred'      => $resource->articles->where('keep', true)
                                                          ->count()
        ];
    }
}

