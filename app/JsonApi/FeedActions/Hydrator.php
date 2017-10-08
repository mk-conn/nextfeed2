<?php

namespace App\JsonApi\FeedActions;


use CloudCreativity\JsonApi\Contracts\Hydrator\HydratesRelatedInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceObjectInterface;
use CloudCreativity\JsonApi\Hydrator\AbstractHydrator;
use CloudCreativity\JsonApi\Hydrator\RelatedHydratorTrait;
use CloudCreativity\Utils\Object\StandardObjectInterface;

class Hydrator extends AbstractHydrator implements HydratesRelatedInterface
{

    use RelatedHydratorTrait;

    protected function hydrateAttributes(StandardObjectInterface $attributes, $record)
    {
        $query = $attributes->get('query');

        $record->setAttribute('query', $query);
    }

    /**
     * @param StandardObjectInterface $attributes
     * @param                         $record
     *
     * @return void
     */


    public function hydrateRelated(ResourceObjectInterface $resource, $record)
    {
        // TODO: Implement hydrateRelated() method.
    }

}
