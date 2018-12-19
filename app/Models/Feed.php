<?php

namespace App\Models;


use App\BaseModel;
use App\Providers\FeedServiceProvider;
use App\Traits\Model\HasOrder;
use Carbon\Carbon;
use DOMXPath;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Feed\AbstractFeed;
use Zend\Http\Client;

/**
 * Class Feed
 *
 * @package App\Models
 * @property int                                                                 $id
 * @property string                                                              $guid
 * @property string                                                              $name
 * @property int|null                                                            $folder_id
 * @property int                                                                 $user_id
 * @property string|null                                                         $description
 * @property string                                                              $url
 * @property string                                                              $feed_url
 * @property string                                                              $site_url
 * @property string|null                                                         $icon
 * @property string|null                                                         $logo
 * @property string|null                                                         $language
 * @property string|null                                                         $etag
 * @property string|null                                                         $auth_user
 * @property string|null                                                         $auth_password
 * @property int|null                                                            $order
 * @property string|null                                                         $update_error
 * @property \Carbon\Carbon|null                                                 $created_at
 * @property \Carbon\Carbon|null                                                 $updated_at
 * @property \Carbon\Carbon|null                                                 $last_modified
 * @property array                                                               $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \App\Models\Folder|null                                        $folder
 * @property-read \App\Models\User                                               $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereAuthPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereAuthUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereEtag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUpdateError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUserId($value)
 * @mixin \Eloquent
 */
class Feed extends BaseModel
{
    use HasOrder;

    /**
     *
     */
    const TABLE = 'feeds';
    /**
     * @var bool
     */
    protected static $baseObserver = false;
    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return bool
     */
    public function fetchIcon()
    {
        $request = new \Zend\Http\Request();
        $request->setUri($this->site_url);
        $request->setMethod('GET');
        $client = new Client();
        $response = $client->send($request);
        $body = $response->getBody();

        if (!empty($body)) {

            try {
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHtml($body);
                $xpath = new DOMXpath($dom);
                $elements = $xpath->query('//link[@rel="icon" or @rel="shortcut icon" or @rel="Shortcut Icon" or @rel="icon shortcut"]');
                for ($i = 0; $i < $elements->length; ++$i) {
                    $icons[] = $elements->item($i)
                                        ->getAttribute('href');
                }
                if (!empty($icons)) {
                    $this->icon = $this->site_url . $icons[ 0 ];

                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    /**
     * @param $lastArticleId
     *
     * @return bool|int
     */
    public function read($lastArticleId)
    {
        // get last articles updated-at, than, set all that are before or equal this date to 'read: true' so new
        // unread will not be set to read:true - makes sense?

        $lastArticle = Article::find($lastArticleId);
        $updatedDate = $lastArticle->updated_at;

        return Article::where('feed_id', $this->id)
                      ->where('read', false)
                      ->where('updated_at', '<=', $updatedDate)
                      ->orderBy('udpated-date', 'desc')
                      ->update(['read' => true]);
    }

    /**
     *
     */
    public function fetchNewArticles()
    {
        $lastEtag = $this->etag;
        $lastModified = $this->last_modified;
        /** @var AbstractFeed $feed */
        $feed = app()->make(
            'FeedReader',
            ['uri' => $this->feed_url, 'etag' => $lastEtag, 'last_modfied' => $lastModified]
        );

        $this->detectEtagAndLastModified($feed);
        $this->storeArticles($feed);

        $this->save();
    }

    /**
     * @param AbstractFeed $feed
     */
    public function detectEtagAndLastModified(AbstractFeed $feed)
    {
        $receivedLastModified = $feed->getDateModified();
        /** @var  $httpClient */
        $httpClient = resolve(FeedServiceProvider::FEED_READER_HTTP_CLIENT);
        /** @var Client $client */
        if ($etag = $httpClient->getDecoratedClient() !== false) {
            $this->etag = $etag;
        }
        $this->last_modified = $receivedLastModified;
    }

    /**
     * @param AbstractFeed $feed
     */
    public function storeArticles(AbstractFeed $feed)
    {
        $user = $this->user;
        /** @var EntryInterface $entry */
        foreach ($feed as $entry) {
            /** @var Article $article */
            $article = Article::firstOrNew(['guid' => $entry->getId()]);
            if ($article->exists) {
                // see if we really have to update here
                $entryMD5 = md5($entry->getTitle() . $entry->getDescription() . $entry->getContent());
                $articleMD5 = md5($article->title . $article->description . $article->content);
                if ($articleMD5 === $entryMD5) {
                    continue;
                }
            }
            $article->createFromFeedEntry($entry);

            $article->feed()
                    ->associate($this);
            $article->user()
                    ->associate($user);
            $article->save();
        }
    }

    public function createFromChannel(AbstractFeed $feed)
    {
        $this->guid = $feed->getId();
        $this->description = $feed->getDescription();
        $this->site_url = $feed->getLink();
        $this->feed_url = $feed->getFeedLink();
        $this->language = $feed->getLanguage();
        $this->logo = $feed->getImage();
        $this->name = $feed->getTitle();
        $this->detectEtagAndLastModified($feed);
    }

    /**
     *
     * @param int  $days
     * @param bool $force
     *
     * @return int
     */
    public function cleanup(int $days = 0, $force = false)
    {
        if (!$force) {
            if (isset($this->settings)) {
                $settings = $this->settings;
                $days = array_get($settings, 'articles.keep');
            }
        }

        if ((int) $days === 0) {
            // no setting means, to keep em all
            return 0;
        }

        $dateFormat = self::dateFormat();
        $maxUpatedDate = Carbon::create()
                               ->subDays($days)
                               ->format($dateFormat);

        $articles = Article::where('updated_date', '<', $maxUpatedDate)
                           ->where('feed_id', $this->id)
                           ->where('keep', false);
        if (!$force) {
            $articles->where('read', true);
        };;
        $count = $articles->delete();

        return $count;
    }
}
