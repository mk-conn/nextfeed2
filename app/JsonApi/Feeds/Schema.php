<?php

namespace App\JsonApi\Feeds;


use App\JsonApi\DefaultSchema;
use App\Models\Feed;

/**
 * Class Schema
 *
 * @package App\JsonApi\Feeds
 */
class Schema extends DefaultSchema
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

        $includes[ 'articles' ] = [
            self::SHOW_SELF    => true,
            self::SHOW_RELATED => true,
            self::SHOW_DATA    => isset($includeRelationships[ 'articles' ]),
            self::DATA         => function () use ($resource) {
                return $resource->articles;
            }
        ];


        $includes[ 'folder' ] = [
            self::SHOW_SELF    => true,
            self::SHOW_RELATED => true,
            self::SHOW_DATA    => isset($includeRelationships[ 'folder' ]),
            self::DATA         => function () use ($resource) {
                return $resource->folder;
            }
        ];
        $includes  [ 'user' ] = [
            self::SHOW_SELF    => true,
            self::SHOW_RELATED => true,
            self::SHOW_DATA    => isset($includeRelationships[ 'user' ]),
            self::DATA         => function () use ($resource) {
                return $resource->user;
            }
        ];

        return $includes;
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

