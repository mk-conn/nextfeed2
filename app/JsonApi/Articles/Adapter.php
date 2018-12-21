<?php

namespace App\JsonApi\Articles;


use App\JsonApi\DefaultAdapter;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class Adapter
 *
 * @package App\JsonApi\Articles
 */
class Adapter extends DefaultAdapter
{
    /**
     * Model
     */
    const MODEL = Article::class;
    /**
     * @var array
     */
    protected $fillable = ['keep', 'read'];

    /**
     * @return \CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo
     */
    public function feed()
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

        if ($filters->has('feed')) {
            $query->where('feed_id', $filters->get('feed'));
        }
        $query->where('user_id', $user->id);

        if ($filters->has('read')) {
            $query->where('read', $filters->get('read'));
        }

        if ($filters->has('keep')) {
            $query->where('keep', $filters->get('keep'));
        }
    }
}
