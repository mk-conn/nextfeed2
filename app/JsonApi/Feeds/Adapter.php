<?php

namespace App\JsonApi\Feeds;


use App\Models\Feed;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use CloudCreativity\LaravelJsonApi\Store\EloquentAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Adapter extends EloquentAdapter
{

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Feed(), $paging);
    }

    /**
     * @param Builder    $query
     * @param Collection $filters
     *
     * @return void
     */
    protected function filter(Builder $query, Collection $filters)
    {
        $user = Auth::user();

        if (!$user) {
            throw new UnauthorizedHttpException('jwt-auth', 'Invalid user.');
        }

        $query->whereNull('folder_id');
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
