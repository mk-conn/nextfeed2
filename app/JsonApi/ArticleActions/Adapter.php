<?php

namespace App\JsonApi\ArticleActions;


use App\Models\ArticleAction;
use CloudCreativity\JsonApi\Contracts\Store\AdapterInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class Adapter implements AdapterInterface
{

    /**
     * @param EncodingParametersInterface $parameters
     *
     * @return mixed
     */
    public function query(EncodingParametersInterface $parameters)
    {
        $articleAction = new ArticleAction();

        $data = $parameters->getUnrecognizedParameters();
        $action = $data['action'];
        $articleAction->setParams($data['params']);

        $articleAction->$action();

        return $articleAction;
    }

    /**
     * @param $resourceId
     *
     * @return bool
     */
    public function exists($resourceId)
    {
    }

    /**
     * @param string $resourceId
     *
     * @return object|null
     */
    public function find($resourceId)
    {
    }

}
