<?php

namespace App\JsonApi\Folders;


use App\JsonApi\DefaultSchema;
use App\Models\Folder;

class Schema extends DefaultSchema
{

    /**
     * @var string
     */
    protected $resourceType = 'folders';

    /**
     * @var array|null
     */
    protected $attributes = [
        'name',
        'order',
        'open'
    ];

    /**
     * @param Folder $resource
     * @param bool   $isPrimary
     * @param array  $includeRelationships
     *
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        $included = [];

        if (isset($includeRelationships[ 'feeds' ])) {
            $included[ 'feeds' ] = [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => function () use ($resource) {
                    return $resource->feeds;
                }
            ];
        }

        return array_merge($included, [
                'user' => [
                    self::SHOW_SELF    => true,
                    self::SHOW_RELATED => true,
                    self::SHOW_DATA    => true,
                    self::DATA         => function () use ($resource) {
                        return $resource->user;
                    }
                ]
            ]
        );
    }
}

