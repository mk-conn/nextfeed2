<?php

namespace App\Models;


use App\BaseModel;
use Carbon\Carbon;
use Laravel\Scout\Searchable;
use PicoFeed\Config\Config;
use PicoFeed\Parser\Item;
use PicoFeed\Scraper\Scraper;

/**
 * Class Article
 *
 * @package App\Models
 * @property int                   $id
 * @property string                $guid
 * @property int                   $feed_id
 * @property string                $title
 * @property string                $author
 * @property string|null           $language
 * @property string                $publish_date
 * @property string                $updated_date
 * @property string|null           $content
 * @property string|null           $description
 * @property string                $url
 * @property array                 $categories
 * @property bool                  $read
 * @property bool                  $keep
 * @property \Carbon\Carbon|null   $created_at
 * @property \Carbon\Carbon|null   $updated_at
 * @property string|null           $searchable
 * @property int                   $user_id
 * @property-read \App\Models\Feed $feed
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereKeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereSearchable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends BaseModel
{
    use Searchable;

    /**
     *
     */
    const TABLE = 'articles';

    /**
     * @var array
     */
    protected $casts = [
        'categories' => 'array'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'searchable',
    ];

    /**
     * @var array
     */
    protected $with = ['feed'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    /**
     * @param Item $item
     */
    public function createFromFeedItem(Item $item)
    {
        $this->title = $item->getTitle();
        $this->author = $item->getAuthor();
        $this->content = $item->getContent();
        $this->guid = $item->getId();
        $this->url = $item->getUrl();
        $this->publish_date = $item->getPublishedDate();
        $this->updated_date = $item->getUpdatedDate();
        $this->categories = $item->getCategories();
        $this->description = $this->parseDescription($item);
    }

    /**
     * @param $item
     *
     * @return null|string
     */
    protected function parseDescription($item)
    {
        $description = null;
        $xml = $item->getXml();

        if (isset($xml->description)) {
            $description = $xml->description;
        } else if (isset($xml->summary)) {
            $description = $xml->summary;
        }

        if (is_array($description)) {
            $description = implode(' ', $description);
        }

        return $description;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getPublishDateAttribute($value)
    {
        return $this->getISODate($value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getISODate($value)
    {
        $time = strtotime($value);

        if ($time) {
            return Carbon::createFromTimestamp($time)
                         ->format(Carbon::ISO8601);
        }

        return $value;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getUpdatedDateAttribute($value)
    {
        return $this->getISODate($value);
    }

    /**
     * @return array
     */
    public function searchableOptions()
    {
        // pgsql related
        return [
            'external'       => false,
            // If you don't want scout to maintain the index for you
            // You can turn it off either for a Model or globally
            'maintain_index' => true,
            'config'         => 'simple'
        ];
    }

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'author'  => $this->author,
            'feed'    => $this->feed->name,
            'user_id' => $this->user->id
        ];
    }

    public function scrape()
    {
        $config = new Config;

        $grabber = new Scraper($config);
        $grabber->setUrl($this->url);
        $grabber->execute();

// Get raw HTML content
//        $html = $grabber->getRawContent();

//// Get relevant content
        $html = $grabber->getRelevantContent();
//
//// Get filtered relevant content
//        echo $grabber->getFilteredContent();

// Return true if there is relevant content
        return $html;
    }

//    /**
//     * @return array
//     */
//    public function searchableAdditionalArray()
//    {
//        return [
//            'user_id' => $this->user->id,
//        ];
//    }
}
