<?php

namespace App\JsonApi\Articles;


use App\Models\Article;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use CloudCreativity\LaravelJsonApi\Store\EloquentAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Adapter extends EloquentAdapter
{

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Article(), $paging);
    }

    /**
     * @param Builder    $query
     * @param Collection $filters
     *
     * @return void
     */
    protected function filter(Builder $query, Collection $filters)
    {
        if ($filters->has('feed')) {
            $query->where('feed_id', $filters->get('feed'));
        }

        if ($filters->has('read')) {
            $query->where('read', $filters->get('read'));
        }

        if ($filters->has('keep')) {
            $user = Auth::user();
            $feeds = $user->feeds;

            $query->where('keep', $filters->get('keep'))
                  ->whereIn('feed_id', $feeds->pluck('id'));
        }
    }

    /**
     * @param Collection $filters
     *
     * @return mixed
     */
    protected function isSearchOne(Collection $filters)
    {
        // TODO
    }

}
