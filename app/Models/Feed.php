<?php

namespace App\Models;


use App\BaseModel;
use App\Traits\Model\HasOrder;
use Carbon\Carbon;
use PicoFeed\Reader\Favicon;
use PicoFeed\Reader\Reader;

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
     *
     */
    const TABLE = 'feeds';

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
        return Article::where('feed_id', $this->id)
                      ->where('read', false)
                      ->where('id', '<=', $lastArticleId)
                      ->update(['read' => true]);
    }

    /**
     *
     */
    public function fetchNewArticles()
    {
        $etag = $this->etag;
        $lastModified = $this->updated_at;

        $reader = new Reader();
        $resource = $reader->download($this->feed_url, $lastModified, $etag);

        if ($resource->isModified()) {
            $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
            $parsedFeed = $parser->execute();

            $items = collect($parsedFeed->getItems());

            $guids = $items->pluck('id');
            $exisitingArticles = Article::whereIn('guid', $guids)
                                        ->get();

            foreach ($exisitingArticles as $article) {
                $item = $items->where('id', $article->guid)
                              ->first();

                $updated = false;

                if ($article->content !== $item->content) {
                    $article->content = $item->content;
                    $updated = true;
                }

                if ($updated) {
                    $article->updated_date = $item->updatedDate;
                    $article->save();
                }
            }

            $existingItems = $exisitingArticles->pluck('guid');
            $items = $items->whereNotIn('id', $existingItems);

            foreach ($items as $item) {
                $article = new Article();
                $article->createFromFeedItem($item);
                $article->feed()
                        ->associate($this);
                $article->save();
            }

            $this->etag = $resource->getEtag();
            $this->save();
        }
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

        $count = Article::where('updated_at', '<', $maxUpatedDate)
                        ->where('feed_id', $this->id)
                        ->where('read', true)
                        ->where('keep', false)
                        ->delete();

        return $count;
    }
}
