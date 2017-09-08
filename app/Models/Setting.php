<?php

namespace App\Models;


use App\BaseModel;


/**
 * Class Setting
 *
 * @package App\Models
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 */
class Setting extends BaseModel
{
    /**
     *
     */
    const TABLE = 'settings';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
