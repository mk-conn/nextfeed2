<?php

namespace App\Models;


use App\BaseModel;
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
 * @property int|null                                                            $articles_per_update
 * @property string|null                                                         $update_error
 * @property \Carbon\Carbon|null                                                 $created_at
 * @property \Carbon\Carbon|null                                                 $updated_at
 * @property string|null                                                         $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \App\Models\Folder|null                                        $folder
 * @property-read \App\Models\User                                               $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereArticlesPerUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereAuthPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereAuthUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereDeletedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUpdateError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feed whereUserId($value)
 * @mixin \Eloquent
 */
class Feed extends BaseModel
{
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
    public function fetchNewArticles()
    {
        $etag = $this->etag;
        $lastModified = $this->updated_at;

        $reader = new Reader();
        $resource = $reader->download($this->feed_url, $lastModified, $etag);

        if ($resource->isModified()) {
            $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
        }

        $parsedFeed = $parser->execute();

        $items = $parsedFeed->getItems();

        $this->etag = $resource->getEtag();

    }

}
