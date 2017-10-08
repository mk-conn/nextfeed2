<?php

namespace App\JsonApi\FeedActions;


use App\Models\FeedAction;
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
        $feedAction = new FeedAction();

        $data = $parameters->getUnrecognizedParameters();
        $action = $data['action'];
        $feedAction->setParams($data['params']);

        $feedAction->$action();

        return $feedAction;

    }

    /**
     * @param $resourceId
     *
     * @return bool
     */
    public function exists($resourceId)
    {
        $find = true;
    }

    /**
     * @param string $resourceId
     *
     * @return object|null
     */
    public function find($resourceId)
    {
        $break = true;
    }

}
