<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers\Api;


use App\JsonApi\Articles;
use App\Models\Article;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

/**
 * Class ArticlesController
 *
 * @package App\Http\Controllers\Api
 */
class ArticlesController extends EloquentController
{
    /**
     * ArticlesController constructor.
     *
     * @param Articles\Hydrator $hydrator
     */
    public function __construct(Articles\Hydrator $hydrator)
    {
        parent::__construct(new Article(), $hydrator);
    }
}
