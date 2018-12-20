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
use Zend\Feed\Reader\Feed\AbstractFeed;
use Zend\Feed\Reader\Reader;

/**
 * Class FeedObserver
 *
 * @package App\Observers
 */
class FeedObserver extends BaseObserver
{
    /**
     * @var Reader
     */
    protected $feedReader;

    /**
     * @var
     */
    protected $articles;

    /**
     * @param Model $model
     *
     * @return mixed
     * @throws \PicoFeed\Parser\MalformedXmlException
     * @throws \PicoFeed\Reader\SubscriptionNotFoundException
     * @throws \PicoFeed\Reader\UnsupportedFeedFormatException
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
