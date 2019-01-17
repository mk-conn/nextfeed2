<?php

namespace App\JsonApi\Feeds;


use App\JsonApi\DefaultAdapter;
use App\Models\Feed;
use Illuminate\Support\Collection;

/**
 * Class Adapter
 *
 * @package App\JsonApi\Feeds
 */
class Adapter extends DefaultAdapter
{
    /**
     * Model
     */
    const MODEL = Feed::class;

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo
     */
    public function folder()
    {
        return $this->belongsTo();
    }

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\HasMany
     */
    public function articles()
    {
        return $this->hasMany();
    }

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Collection                            $filters
     */
    protected function filter($query, Collection $filters)
    {
        $user = \Auth::user('api');

        $query->where('user_id', $user->getAuthIdentifier());
    }

}
