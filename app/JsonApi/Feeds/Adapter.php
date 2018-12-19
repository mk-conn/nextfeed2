<?php

namespace App\JsonApi\Feeds;


use App\JsonApi\DefaultAdapter;
use App\Models\Feed;

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

}
