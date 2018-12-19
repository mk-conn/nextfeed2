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
use Illuminate\Database\Eloquent\Model;

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
        $resource = $this->feedReader->discover($model->url);
        $etag = $resource->getEtag();

        $feedParser = $this->feedReader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        $parsedFeed = $feedParser->execute();

        $model->guid = $parsedFeed->getId();
        $model->description = $parsedFeed->getDescription();
        $model->site_url = $parsedFeed->getSiteUrl();
        $model->feed_url = $parsedFeed->getFeedUrl();
        $model->language = $parsedFeed->getLanguage();
        $model->logo = $parsedFeed->getLogo();
        $model->icon = $parsedFeed->getIcon();
        $model->name = $parsedFeed->getTitle();
        $model->etag = $etag;

        if (!$model->icon) {
            $model->fetchIcon();
        }

        if (!empty($items = $parsedFeed->getItems())) {
            $parsedFeedHelper = new ParsedFeed();
            $parsedFeedHelper->items = collect($items);

            app()->instance(ParsedFeed::class, $parsedFeedHelper);
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
        $model->fetchNewArticles();

        return parent::created($model);
    }

}
