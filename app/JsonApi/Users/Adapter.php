<?php

namespace App\JsonApi\Users;

use App\JsonApi\DefaultAdapter;
use App\Models\User;
use Auth;
use Illuminate\Support\Collection;

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
    
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Collection                            $filters
     */
    protected function filter($query, Collection $filters)
    {
        if ($filters->has('me')) {
            $user = Auth::user('api');
            $userId = $user->getAuthIdentifier();
            if ($userId) {
                $query->where('id', $userId);
            }
        }
    }
    
    /**
     * @param \Illuminate\Support\Collection $filters
     *
     * @return bool
     */
    protected function isSearchOne(Collection $filters)
    {
        return $filters->has('me');
    }
    
}
