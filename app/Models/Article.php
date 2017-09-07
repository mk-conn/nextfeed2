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
 * Class Article
 *
 * @package App\Models
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
