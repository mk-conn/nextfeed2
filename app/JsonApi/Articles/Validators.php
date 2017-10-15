<?php

namespace App\JsonApi\Articles;


use App\JsonApi\BaseValidator;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;

class Validators extends BaseValidator
{

    protected $allowedSortParameters = [
        'publish-date',
        'title',
        'author',
        'updated-date',
        'id'
    ];

    protected $allowedFieldSetTypes = null;

    protected $allowedFilteringParameters = [
        'feed',
        'keep',
        'read'
    ];

    protected $allowedIncludePaths = ['feed'];

    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * Get the validation rules for the resource attributes.
     *
     * @param object|null $record
     *      the record being updated, or null if it is a create request.
     *
     * @return array
     */
    protected function attributeRules($record = null)
    {
        return [
            //
        ];
    }

    /**
     * Define the validation rules for the resource relationships.
     *
     * @param RelationshipsValidatorInterface $relationships
     * @param object|null                     $record
     *      the record being updated, or null if it is a create request.
     *
     * @return void
     */
    protected function relationshipRules(RelationshipsValidatorInterface $relationships, $record = null)
    {
        //
    }

}
