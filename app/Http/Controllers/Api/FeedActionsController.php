<?php


namespace App\Http\Controllers\Api;


use App\JsonApi\FeedActions\Hydrator;
use App\Models\FeedAction;
use CloudCreativity\JsonApi\Contracts\Http\Requests\RequestInterface;
use CloudCreativity\JsonApi\Contracts\Object\ResourceObjectInterface;
use CloudCreativity\JsonApi\Contracts\Store\StoreInterface;
use CloudCreativity\LaravelJsonApi\Http\Controllers\CreatesResponses;
use Illuminate\Routing\Controller;

/**
 * Class FeedActionsController
 *
 * @package App\Http\Controllers\Api
 */
class FeedActionsController extends Controller
{
    use CreatesResponses;


    /**
     * @param Hydrator                $hydrator
     * @param ResourceObjectInterface $resource
     *
     * @return mixed
     */
    public function create(Hydrator $hydrator, ResourceObjectInterface $resource)
    {
//
//        return $this->reply()
//                    ->created($feedAction->results());
    }

    /**
     * @param StoreInterface   $store
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function index(StoreInterface $store, RequestInterface $request)
    {

        $result = $store->query(
            $request->getResourceType(),
            $request->getParameters()
        );

        return $this->reply()
                    ->content($result);

    }

    /**
     * @param FeedAction $record
     *
     * @return mixed
     */
    public function read(FeedAction $record)
    {
        return $this->reply()
                    ->content($record);
    }

    /**
     * @param Hydrator                $hydrator
     * @param ResourceObjectInterface $resource
     */
    public function update(Hydrator $hydrator, ResourceObjectInterface $resource)
    {

    }


}
