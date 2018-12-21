<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Observers;


use App\Helpers\ParsedFeed;
use App\Models\Feed;
use App\Readers\FeedReader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Zend\Feed\Reader\Feed\AbstractFeed;

/**
 * Class FeedObserver
 *
 * @package App\Observers
 */
class FeedObserver extends BaseObserver
{

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function creating(Model $model)
    {


        /** @var Feed $model */
        /** @var  AbstractFeed $feedInterface */
        /** @var FeedReader $feedReader */
        $feedReader = app()->make(FeedReader::class);
        $feedInterface = $feedReader->read(['uri' => $model->feed_url]);

        $model->createFromChannel($feedInterface);
        $model->etag = $feedReader->getEtag($model->feed_url);
        $model->last_modified = $feedReader->getLastModified($model->feed_url);
        $model->attachFeedInterface($feedInterface);
        if (!$model->icon) {
            $model->fetchIcon();
        }

        if (!$model->user) {
            $user = Auth::guard('api')
                        ->user();
            $model->user()
                  ->associate($user);
        }


        return parent::creating($model);
    }

    /**
     * @param Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function created(Model $model)
    {

        /** @var Feed $model */
        $model->storeArticles($model->getFeedInterface());

        return parent::created($model);
    }

}
