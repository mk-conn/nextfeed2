<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Observers;


use App\Models\Feed;
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

        $feed->description = $parsedFeed->getDescription();
        $feed->site_url = $parsedFeed->getSiteUrl();
        $feed->feed_url = $parsedFeed->getFeedUrl();
        $feed->language = $parsedFeed->getLanguage();
        $feed->logo = $parsedFeed->getLogo();
        $feed->name = $parsedFeed->getTitle();
        $feed->etag = $etag;

        if (!empty($articles = $parsedFeed->getItems())) {
            $this->articles = $articles;
        }
    }

    /**
     * @param Feed $feed
     */
    public function created(Feed $feed)
    {
        if ($this->articles) {
            $feed->articles()
                 ->saveMany($this->articles);
        }
    }

}
