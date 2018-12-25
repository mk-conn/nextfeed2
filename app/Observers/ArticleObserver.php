<?php


namespace App\Observers;


use App\Models\Article;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleObserver
 *
 * @package App\Observers
 */
class ArticleObserver extends BaseObserver
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updating(Model $model)
    {
        $dirty = $model->getDirty();
        
        if (count($dirty) === 1) {
            if (array_intersect(['keep', 'read'], array_keys($dirty))) {
                Article::disableAuditing();
            }
        }
        
        return $model;
    }
    
}