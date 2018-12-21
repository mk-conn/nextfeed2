<?php


namespace App\Observers;


use App\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FolderObserver extends BaseObserver
{
    /**
     * @param Model $model
     *
     * @return Model
     */
    public function creating(Model $model)
    {
        /** @var Folder $model */
        if (!$model->user) {
            $user = Auth::guard('api')
                        ->user();
            $model->user()
                  ->associate($user);
        }

        return $model;
    }

}