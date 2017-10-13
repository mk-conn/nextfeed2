<?php
/**
 * Created by PhpStorm.
 * User: mkruege
 * Date: 13.10.17
 * Time: 13:30
 */

namespace App\JsonApi;

use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;
use CloudCreativity\LaravelJsonApi\Validators\AbstractValidatorProvider;

class BaseValidator extends AbstractValidatorProvider
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

    protected function attributeRules($record = null)
    {

    }

    protected function relationshipRules(RelationshipsValidatorInterface $relationships, $record = null)
    {

    }


}
