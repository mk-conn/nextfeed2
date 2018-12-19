<?php

namespace App\Models;


use App\BaseModel;
use App\Traits\Model\HasOrder;
use Carbon\Carbon;
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
     *
     */
    public function fetchIcon()
    {
        $favicon = new Favicon();
        $icon_link = $favicon->find($this->site_url);
        $this->icon = $icon_link;
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
        $user = $this->user;
        /** @var AbstractFeed $feed */
        $feed = app()->make(
            'FeedReader',
            [$this->feed_url, $lastEtag, $lastModified]
        );

        $receivedLastModified = $feed->getDateModified();
        /** @var  $httpClient */
        $httpClient = resolve('FeedHttpClient');
        /** @var Client $client */
        if ($etag = $httpClient->getDecoratedClient() !== false) {
            $this->etag = $etag;
        }
        $this->last_modified = $receivedLastModified;
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

        $this->save();
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
