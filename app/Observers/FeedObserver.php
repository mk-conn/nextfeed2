<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Observers;


use App\Helpers\ParsedFeed;
use App\Models\Article;
use App\Models\Feed;
use PicoFeed\Parser\Item;
use PicoFeed\Reader\Reader;

/**
 * Class FeedObserver
 *
 * @package App\Observers
 */
class FeedObserver
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
     * @param Feed $feed
     */
    public function creating(Feed $feed)
    {
        $resource = $this->feedReader->discover($feed->url);
        $etag = $resource->getEtag();

        $feedParser = $this->feedReader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        $parsedFeed = $feedParser->execute();

        $feed->guid = $parsedFeed->getId();
        $feed->description = $parsedFeed->getDescription();
        $feed->site_url = $parsedFeed->getSiteUrl();
        $feed->feed_url = $parsedFeed->getFeedUrl();
        $feed->language = $parsedFeed->getLanguage();
        $feed->logo = $parsedFeed->getLogo();
        $feed->name = $parsedFeed->getTitle();
        $feed->etag = $etag;

        if (!empty($items = $parsedFeed->getItems())) {
            $parsedFeedHelper = new ParsedFeed();
            $parsedFeedHelper->items = $items;

            app()->instance(ParsedFeed::class, $parsedFeedHelper);
        }
    }

    /**
     * @param Feed $feed
     */
    public function created(Feed $feed)
    {
        /** @var ParsedFeed $parsedFeedHelper */
        $parsedFeedHelper = resolve(ParsedFeed::class);
        $items = $parsedFeedHelper->items;
        $articles = collect([]);

        /** @var Item $item */
        foreach ($items as $item) {

            $article = new Article(
                [
                    'title'        => $item->getTitle(),
                    'author'       => $item->getAuthor(),
                    'content'      => $item->getContent(),
                    'guid'         => $item->getId(),
                    'description'  => $item->getXml()->description,
                    'url'          => $item->getUrl(),
                    'publish_date' => $item->getPublishedDate(),
                    'updated_date' => $item->getUpdatedDate(),
                    'categories'   => $item->getCategories()
                ]
            );

            $article->feed()
                    ->associate($feed);

            $articles->push($article);
        }

        if ($articles->count()) {
            $feed->articles()
                 ->saveMany($articles);
        }

    }

}
