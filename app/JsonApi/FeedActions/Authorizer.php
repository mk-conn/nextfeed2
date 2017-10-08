<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\JsonApi\FeedActions;


use App\JsonApi\BaseAuthorizer;
use CloudCreativity\JsonApi\Contracts\Object\RelationshipInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceObjectInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

/**
 * Class Authorizer
 *
 * @package App\JsonApi\Folders
 */
class Authorizer extends BaseAuthorizer
{

    /**
     * @param string                      $resourceType
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canReadMany($resourceType, EncodingParametersInterface $parameters)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->forbidden();
        }

        return true;
    }


    /**
     * @param string                      $resourceType
     * @param ResourceObjectInterface     $resource
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canCreate($resourceType, ResourceObjectInterface $resource, EncodingParametersInterface $parameters)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->forbidden();
        }

        return true;
    }


    /**
     * @param Feed                        $record
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canRead($record, EncodingParametersInterface $parameters)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->forbidden();
        }

        return true;
    }


    /**
     * @param object                      $record
     * @param ResourceObjectInterface     $resource
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canUpdate($record, ResourceObjectInterface $resource, EncodingParametersInterface $parameters)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->forbidden();
        }

        return true;
    }


    /**
     * @param object                      $record
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canDelete($record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    /**
     * @param                             $relationshipKey
     * @param                             $record
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canReadRelatedResource($relationshipKey, $record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    /**
     * @param string                      $relationshipKey
     * @param object                      $record
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canReadRelationship($relationshipKey, $record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    /**
     * @param string                      $relationshipKey
     * @param Feed                        $record
     * @param RelationshipInterface       $relationship
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canModifyRelationship(
        $relationshipKey,
        $record,
        RelationshipInterface $relationship,
        EncodingParametersInterface $parameters
    )
    {

        return false;
    }

}
