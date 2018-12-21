<?php

namespace App\Models;


use App\BaseModel;
use App\Readers\FeedReader;
use App\Traits\Model\HasOrder;
use Carbon\Carbon;
use DOMXPath;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Feed\AbstractFeed;
use Zend\Feed\Reader\Feed\FeedInterface;
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
     * @var FeedInterface
     */
    protected $feedInterface = null;
    
    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];
    /**
     * @var array
     */
    protected $fetchErrors = [];
    
    /**
     * @param $feedInterface
     *
     * @return $this
     */
    public function attachFeedInterface(FeedInterface $feedInterface)
    {
        $this->feedInterface = $feedInterface;
        
        return $this;
    }
    
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
                $elements = $xpath->query(
                    '//link[@rel="icon" or @rel="shortcut icon" or @rel="Shortcut Icon" or @rel="icon shortcut"]');
                for ($i = 0; $i < $elements->length; ++$i) {
                    $icons[] = $elements->item($i)
                                        ->getAttribute('href');
                }
                if (!empty($icons)) {
                    $this->icon = rtrim($this->site_url, "\t\n\r\0\x0B/") . $icons[0];
                    
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
                      ->where('id', '<=', $lastArticleId)
//                      ->orderBy('udpated-date', 'desc')
                      ->update(['read' => true]);
    }
    
    /**
     *
     */
    public function fetchNewArticles()
    {
        $lastEtag = $this->etag;
        $lastModified = $this->last_modified;
        $saved = 0;
        /** @var FeedReader $feedReader */
        $feedReader = app()->make(FeedReader::class);
        $feed = $feedReader->read(
            [
                FeedReader::URI           => $this->feed_url,
                FeedReader::ETAG          => $lastEtag,
                FeedReader::LAST_MODIFIED => $lastModified
            ]);
        
        if ($feed) {
            $saved = $this->storeArticles($feed);
        }
        $this->etag = $feedReader->getEtag($this->feed_url);
        $this->last_modified = $feedReader->getLastModified($this->feed_url);
        
        if (!empty($this->fetchErrors)) {
            $this->update_error = implode("\n\n", $this->fetchErrors);
            $this->fetchErrors = [];
        } else {
            $this->update_error = null;
        }
        
        $this->save();
        
        return $saved;
    }
    
    /**
     * @param FeedInterface $feed
     *
     * @return int
     */
    public function storeArticles(FeedInterface $feed)
    {
        $user = $this->user;
        $count = 0;
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
            
            try {
                $article->createFromFeedEntry($entry);
                $article->feed()
                        ->associate($this);
                $article->user()
                        ->associate($user);
                $article->save();
                $count++;
            } catch (\Exception $e) {
                $this->addFetchError($e->getMessage() . "\n" . $e->getTraceAsString());
            }
        }
        
        return $count;
    }
    
    /**
     * @param AbstractFeed $feed
     */
    public function createFromChannel(AbstractFeed $feed)
    {
        $this->guid = $feed->getId();
        $this->description = $feed->getDescription();
        $this->site_url = $feed->getLink();
        $this->feed_url = $feed->getFeedLink();
        $this->language = $feed->getLanguage();
        $this->logo = $feed->getImage();
        $this->name = $feed->getTitle();
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
        
        if ((int)$days === 0) {
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
    
    /**
     * @return FeedInterface
     */
    public function getFeedInterface()
    {
        return $this->feedInterface;
    }
    
    /**
     * @param $error
     */
    protected function addFetchError($error)
    {
        $this->fetchErrors[] = $error;
    }
}
