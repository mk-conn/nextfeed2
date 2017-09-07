<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers\Api;


use App\JsonApi\Feeds;
use App\Models\Feed;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

/**
 * Class FeedsController
 *
 * @package App\Http\Controllers\Api
 */
class FeedsController extends EloquentController
{
    /**
     * FeedsController constructor.
     *
     * @param Feeds\Hydrator $hydrator
     */
    public function __construct(Feeds\Hydrator $hydrator)
    {
        parent::__construct(new Feed(), $hydrator);
    }
}
