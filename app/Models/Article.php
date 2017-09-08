<?php

namespace App\Models;


use App\BaseModel;

/**
 * Class Article
 *
 * @package App\Models
 * @property int                   $id
 * @property string                $name
 * @property int                   $feed_id
 * @property string                $body
 * @property string                $url
 * @property string                $feed_location
 * @property string                $icon
 * @property \Carbon\Carbon|null   $created_at
 * @property \Carbon\Carbon|null   $updated_at
 * @property string|null           $deleted_at
 * @property-read \App\Models\Feed $feed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereFeedLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUrl($value)
 * @mixin \Eloquent
 */
class Article extends BaseModel
{
    /**
     *
     */
    const TABLE = 'articles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

}
