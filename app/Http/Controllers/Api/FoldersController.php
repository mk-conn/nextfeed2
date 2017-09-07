<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers\Api;


use App\JsonApi\Folders;
use App\Models\Folder;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

/**
 * Class FeedsController
 *
 * @package App\Http\Controllers\Api
 */
class FoldersController extends EloquentController
{
    /**
     * FeedsController constructor.
     *
     * @param Folders\Hydrator $hydrator
     */
    public function __construct(Folders\Hydrator $hydrator)
    {
        parent::__construct(new Folder(), $hydrator);
    }
}
