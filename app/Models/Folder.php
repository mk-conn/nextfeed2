<?php


namespace App\Models;


use App\BaseModel;

/**
 * Class Folder
 *
 * @package App\Models
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
