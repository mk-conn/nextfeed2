<?php

namespace App\JsonApi\Users;

use App\JsonApi\DefaultAdapter;
use App\Models\User;

/**
 * Class Adapter
 *
 * @package App\JsonApi\Users
 */
class Adapter extends DefaultAdapter
{

    /**
     * Model
     */
    const MODEL = User::class;

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\HasMany
     */
    public function feeds()
    {
        return $this->hasMany();
    }

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\HasMany
     */
    public function folder()
    {
        return $this->hasMany();
    }

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\HasMany
     */
    public function settings()
    {
        return $this->hasMany();
    }

}
