<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App;


use App\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App
 */
class BaseModel extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /**
     * @var bool
     */
    protected static $baseObserver = true;

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

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        if (static::$baseObserver) {
            static::observe(BaseObserver::class);
        }
    }

    /**
     * @return string
     */
    public static function dateFormat()
    {
        $self = new self();

        return $self->getDateFormat();
    }

}
