<?php

namespace App\JsonApi\ArticleActions;

/**
 * Class Hydrator
 *
 * @package App\JsonApi\ArticleActions
 */
class Hydrator extends AbstractHydrator implements HydratesRelatedInterface
{

    use RelatedHydratorTrait;

    /**
     * @param StandardObjectInterface $attributes
     * @param                         $record
     */
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
