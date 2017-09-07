<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Models;


use App\BaseModel;

/**
 * Class Feed
 *
 * @package App\Models
 */
class Feed extends BaseModel
{
    const TABLE = 'feeds';

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

}
