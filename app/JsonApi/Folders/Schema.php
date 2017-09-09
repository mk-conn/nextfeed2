<?php

namespace App\JsonApi\Folders;


use App\Models\Folder;
use CloudCreativity\LaravelJsonApi\Schema\EloquentSchema;

class Schema extends EloquentSchema
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
        'order'
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

        if (isset($includeRelationships['feeds'])) {
            $includes['feeds'] = [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => true,
                self::DATA         => $resource->feeds
            ];
        }

        return array_merge($included, [
                'user' => [
                    self::SHOW_SELF    => true,
                    self::SHOW_RELATED => true,
                    self::SHOW_DATA    => true,
                    self::DATA         => isset($includeRelationships['user']) ?
                        $resource->user :
                        $this->createBelongsToIdentity($resource, 'user')
                ]
            ]
        );

    }


}

