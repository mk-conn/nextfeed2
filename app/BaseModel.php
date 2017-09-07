<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App
 */
class BaseModel extends Model
{
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /**
     * BaseModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (defined('static::TABLE')) {
            $this->setTable(constant('static::TABLE'));
        }

        parent::__construct($attributes);
    }
}
