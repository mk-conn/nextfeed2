<?php

namespace App\Models;


use App\BaseModel;
use App\Events\ArticlesFetched;
use App\Readers\FeedReader;
use App\Traits\Model\HasOrder;
use Carbon\Carbon;
use Masterminds\HTML5;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Feed\AbstractFeed;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Http\Client;
use Zend\Http\Request;

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
        $request = new Request();
        $request->setUri($this->site_url);
        $request->setMethod('GET');
        $client = new Client();
        $response = $client->send($request);
        $body = trim($response->getBody());
        $body = (new \tidy)->repairString(
            $body,
            [
                'drop-proprietary-attributes' => true,
                'fix-uri'                     => true,
                'wrap'                        => false,
                //                'input-xml'   => false,
                'output-xml'                  => true,
                'quote-marks'                 => true
            ]
        );
        
        $retrieveImage = function ($imageUrl) {
            $size = @getimagesize($imageUrl);
            
            return isset($size['mime']);
        };
        
        if (!empty($body)) {
            try {
                libxml_use_internal_errors(true);
                $icons[] = 'favicon.ico';
                $html5 = new HTML5(['encode_entities' => true]);
                $doc = $html5->loadHTML($body);
                // xpath did not work for some feeds (probably the html was to fucked up :-/
                $xml = simplexml_import_dom($doc);
                $errors = libxml_get_errors();
                libxml_clear_errors();
                
                if (empty($errors)) {
                    foreach ($xml->head->link as $link) {
                        $rel = $link->attributes()->rel;
                        $rel = strtolower($rel);
                        if ($rel === 'icon' || $rel === 'shortcut icon' || $rel === 'icon shortcut') {
                            $icons = array_prepend($icons, (string)$link->attributes()->href);
                        }
                    }
                }
                
                foreach ($icons as $icon) {
                    $icon = ltrim(trim($icon), '/');
                    $iconPath = ltrim(str_replace($this->site_url, null, $icon), '/');
                    
                    if (strpos($iconPath, '?')) {
                        $iconPath = explode('?', $iconPath)[0];
                    }
                    // check if item is available and not a 404
                    $imageUrl = rtrim($this->site_url, '/') . '/' . $iconPath;
                    if ($retrieveImage($imageUrl)) {
                        $this->icon = $imageUrl;
                        break;
                    }
                }
                
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
    
    
    /**
     *
     * @return bool|int
     */
    public function read()
    {
        return Article::where('feed_id', $this->id)
                      ->where('read', false)
            //->where('id', '<=', $lastArticleId)
            //->orderBy('udpated-date', 'desc')
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
        $feed = null;
        
        try {
            $feed = $feedReader->read(
                [
                    FeedReader::URI           => $this->feed_url,
                    FeedReader::ETAG          => $lastEtag,
                    FeedReader::LAST_MODIFIED => $lastModified
                ]);
        } catch (\Exception $e) {
            $this->fetchErrors[] = $e->getMessage();
        }
        
        if ($feed) {
            if (($saved = $this->storeArticles($feed)) > 0) {
                event(new ArticlesFetched($this));
            }
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
        $logo = null;
        $image = $feed->getImage();
        if ($image) {
            $logo = $image['uri'];
        }
        
        $this->guid = $feed->getId();
        $this->description = $feed->getDescription();
        $this->site_url = $feed->getLink();
        $this->feed_url = $feed->getFeedLink();
        $this->language = $feed->getLanguage();
        $this->logo = $logo;
        $this->name = trim($feed->getTitle());
    }
    
    /**
     * @param int  $days
     * @param bool $force
     *
     * @return bool|int|null
     * @throws \Exception
     */
    public function cleanup(int $days = 0, $force = false)
    {
        if (!$force) {
            if (isset($this->settings)) {
                $settings = $this->settings;
                $days = array_get($settings, 'articles.keep');
            }
            $force = !array_get($settings, 'articles.cleanup.keepUnread');
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
        };
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
