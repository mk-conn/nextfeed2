<?php

namespace App\JsonApi\FeedActions;


use CloudCreativity\JsonApi\Contracts\Hydrator\HydratesRelatedInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceObjectInterface;
use CloudCreativity\JsonApi\Contracts\Object\StandardObjectInterface;
use CloudCreativity\JsonApi\Hydrator\AbstractHydrator;
use CloudCreativity\JsonApi\Hydrator\RelatedHydratorTrait;

class Hydrator extends AbstractHydrator implements HydratesRelatedInterface
{

    use RelatedHydratorTrait;

    /**
     * @param StandardObjectInterface $attributes
     * @param                         $record
     *
     * @return void
     */
    protected function hydrateAttributes(StandardObjectInterface $attributes, $record)
    {
        // TODO
    }

    public function hydrateRelated(ResourceObjectInterface $resource, $record)
    {
        // TODO: Implement hydrateRelated() method.
    }

}
