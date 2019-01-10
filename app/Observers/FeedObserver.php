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
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
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
        try {
            $feedInterface = $feedReader->read(['uri' => $model->feed_url]);
        } catch (\Exception $e) {
            $error = new Error(null, null, null, null, 'Feed creation failed', $e->getMessage());
            throw new JsonApiException($error);
        }

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

        if (!$model->settings || empty($model->settings)) {
            $feedArticlesSettings = config('app-settings.feed.articles');

            $model->settings = [
                'articles' => $feedArticlesSettings
            ];
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
