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
use Auth;

/**
 * Class BaseObserver
 *
 * @package App\Observers
 */
class BaseObserver
{


    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function creating(BaseModel $model)
    {
        $modelReflection = new \ReflectionClass($model);
        if ($modelReflection->hasMethod('user')) {
            $user = Auth::user();

            if ($user) {
                $model->user()
                      ->associate($user);
            }
        }

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
}
