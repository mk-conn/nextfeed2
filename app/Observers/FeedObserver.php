<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Observers;


use App\BaseModel;
use App\Helpers\ParsedFeed;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Support\Collection;
use PicoFeed\Parser\Item;
use PicoFeed\Reader\Reader;

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
     * FeedObserver constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->feedReader = $reader;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function creating(BaseModel $model)
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
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function created(BaseModel $model)
    {
        /** @var Feed $model */
        /** @var ParsedFeed $parsedFeedHelper */
        $parsedFeedHelper = resolve(ParsedFeed::class);
        if ($parsedFeedHelper->items instanceof Collection) {
            $items = $parsedFeedHelper->items->unique('id');
            $articles = collect([]);

            /** @var Item $item */
            foreach ($items as $item) {

                $article = new Article();
                $article->createFromFeedItem($item);

                $article->feed()
                        ->associate($model);

                $articles->push($article);
            }

            if ($articles->count()) {
                $model->articles()
                      ->saveMany($articles);
            }
        }

        return parent::saving($model);
    }

}
