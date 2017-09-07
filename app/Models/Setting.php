<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Models;


use App\BaseModel;
use App\User;

/**
 * Class Setting
 *
 * @package App\Models
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
