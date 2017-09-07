<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers\Api;


use App\JsonApi\Users;
use App\Models\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\Api
 */
class UsersController extends EloquentController
{
    /**
     * UsersController constructor.
     *
     * @param Users\Hydrator $hydrator
     */
    public function __construct(Users\Hydrator $hydrator)
    {
        parent::__construct(new User(), $hydrator);
    }
}
