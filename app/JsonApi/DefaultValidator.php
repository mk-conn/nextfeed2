<?php

namespace App\JsonApi;


use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

/**
 * Class BaseValidator
 *
 * @package App\Api\JsonApi\v1
 */
class DefaultValidator extends AbstractValidators
{
    /**
     * null means: any
     * [] means: none
     * ['yeah']: guess what...
     *
     * @var array
     */
    protected $allowedSortParameters = null;
    /**
     * @var array
     */
    protected $allowedFilteringParameters = null;
    /**
     * @var array
     */
    protected $allowUnrecognizedParameters = false;
    /**
     * @var array
     */
    protected $allowedIncludePaths = [];
    /**
     * @var array
     */
    protected $allowedFieldSetTypes = [];
    /**
     * @var array
     */
    protected $allowedPagingParameters = null;

    protected $queryRules = [
        'page.number' => 'integer|min:1',
        'page.size'   => 'integer|between:1,200',
    ];

    /**
     * @param \CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface $record
     * @param string                                                                        $field
     *
     * @return array
     */
    protected function relationshipRules($record, string $field)
    : array
    {
        return parent::relationshipRules($record, $field);
    }

    /**
     * @param null $record
     *
     * @return array
     */
    protected function rules($record = null)
    : array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function queryRules()
    : array
    {
        return [];
    }


}
