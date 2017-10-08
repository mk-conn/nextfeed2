<?php

namespace App\JsonApi\FeedActions;

use CloudCreativity\JsonApi\Contracts\Store\AdapterInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class Adapter implements AdapterInterface
{

    /**
     * @param EncodingParametersInterface $parameters
     * @return mixed
     */
    public function query(EncodingParametersInterface $parameters)
    {
        $params = $parameters;
    }

    /**
     * @param $resourceId
     * @return bool
     */
    public function exists($resourceId)
    {
        // TODO
    }

    /**
     * @param string $resourceId
     * @return object|null
     */
    public function find($resourceId)
    {
        // TODO
    }

}
