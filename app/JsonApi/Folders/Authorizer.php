<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\JsonApi\Folders;


use App\JsonApi\BaseAuthorizer;
use App\Models\Folder;
use CloudCreativity\JsonApi\Contracts\Object\RelationshipInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceObjectInterface;
use Illuminate\Support\Facades\Auth;
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

        if (isset($parameters->getFilteringParameters()['user'])) {
            $user = $this->currentUser();
            if ($user->name === $parameters->getFilteringParameters()['user']) {
                return true;
            }

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
     * @param object                      $record
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canRead($record, EncodingParametersInterface $parameters)
    {
        if ($record->user->id === Auth::user()->id) {
            return true;
        }

        return $this->forbidden();
    }


    /**
     * @param Folder                      $record
     * @param ResourceObjectInterface     $resource
     * @param EncodingParametersInterface $parameters
     *
     * @return bool
     */
    public function canUpdate($record, ResourceObjectInterface $resource, EncodingParametersInterface $parameters)
    {
        $user = $this->currentUser();
        if ($record->user->id = $user->id) {
            return true;
        }

        return $this->forbidden();
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
     * @param object                      $record
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
