<?php

namespace App\Models;


use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property string|null           $publish_date
 * @property string|null           $updated_date
 * @property string|null           $content
 * @property string|null           $description
 * @property string                $url
 * @property array                 $categories
 * @property bool                  $read
 * @property bool                  $keep
 * @property \Carbon\Carbon|null   $created_at
 * @property \Carbon\Carbon|null   $updated_at
 * @property string|null           $deleted_at
 * @property-read \App\Models\Feed $feed
 * @method bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereKeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withoutTrashed()
 * @mixin \Eloquent
 */
class Article extends BaseModel
{

    use SoftDeletes;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
