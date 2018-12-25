<?php
/**
 * Created by PhpStorm.
 * User: mkruege
 * Date: 10.10.17
 * Time: 15:11
 */

namespace App\Observers;


use App\BaseModel;
use App\Traits\Model\HasOrder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseObserver
 *
 * @package App\Observers
 */
class BaseObserver
{
    
    /**
     * @param Model $model
     *
     * @return Model
     */
    public function creating(Model $model)
    {
        return $model;
    }
    
    /**
     * @param Model $model
     *
     * @return Model
     */
    public function created(Model $model)
    {
        return $model;
    }
    
    
    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function saving(BaseModel $model)
    {
        $uses = class_uses($model);
        
        if (array_key_exists(HasOrder::class, $uses)) {
            $order = $model->getAttribute('order');
            
            if (!$order) {
                $max = (int)$model->max('order');
                $model->setOrderFromMax($max);
            }
        }
        
        return $model;
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updating(Model $model)
    {
        return $model;
    }
}
