<?php


namespace App\Models;


use App\BaseModel;

/**
 * Class Folder
 *
 * @package App\Models
 * @property int                                                              $id
 * @property string                                                           $name
 * @property int                                                              $user_id
 * @property int|null                                                         $order
 * @property bool                                                             $open
 * @property \Carbon\Carbon|null                                              $created_at
 * @property \Carbon\Carbon|null                                              $updated_at
 * @property string|null                                                      $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feed[] $feeds
 * @property-read \App\Models\User                                            $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Folder whereUserId($value)
 * @mixin \Eloquent
 */
class Folder extends BaseModel
{
    /**
     *
     */
    const TABLE = 'folders';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
