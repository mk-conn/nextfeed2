<?php

namespace App\JsonApi\Settings;

use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use CloudCreativity\LaravelJsonApi\Store\EloquentAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Setting;

class Adapter extends EloquentAdapter
{

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Setting(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter(Builder $query, Collection $filters)
    {
        // TODO
    }

    /**
     * @param Collection $filters
     * @return mixed
     */
    protected function isSearchOne(Collection $filters)
    {
        // TODO
    }

}
