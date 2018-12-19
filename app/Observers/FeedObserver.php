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
use App\Providers\FeedServiceProvider;
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
        /** @var  AbstractFeed $feed */
        $feed = app()->make(FeedServiceProvider::FEED_READER, ['uri' => $model->feed_url]);
        app()->instance('Adding_Channel', $feed);

        $model->createFromChannel($feed);
        $model->detectEtagAndLastModified($feed);

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
        $feed = resolve('Adding_Channel');
        /** @var Feed $model */
        $model->storeArticles($feed);

        return parent::created($model);
    }

}
