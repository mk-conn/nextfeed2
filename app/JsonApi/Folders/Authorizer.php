<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\JsonApi\Folders;


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

    public function canReadMany($resourceType, EncodingParametersInterface $parameters)
    {
        return true;
    }


    public function canCreate($resourceType, ResourceObjectInterface $resource, EncodingParametersInterface $parameters)
    {
        return false;
    }


    public function canRead($record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    public function canUpdate($record, ResourceObjectInterface $resource, EncodingParametersInterface $parameters)
    {
        return false;
    }


    public function canDelete($record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    public function canReadRelatedResource($relationshipKey, $record, EncodingParametersInterface $parameters)
    {
        return false;
    }


    public function canReadRelationship($relationshipKey, $record, EncodingParametersInterface $parameters)
    {
        return false;
    }


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
