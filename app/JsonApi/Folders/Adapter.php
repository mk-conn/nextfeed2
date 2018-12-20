<?php

namespace App\JsonApi\Folders;


use App\JsonApi\DefaultAdapter;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class Adapter
 *
 * @package App\JsonApi\Folders
 */
class Adapter extends DefaultAdapter
{
    /**
     *
     */
    const MODEL = Folder::class;

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\HasMany
     */
    public function feeds()
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
     * @param Builder    $query
     * @param Collection $filters
     *
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $user = Auth::guard('api')
                    ->user();

        if ($user) {
            $query->where('user_id', $user->id);
        }
    }

}
