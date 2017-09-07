<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers\Api;


use App\JsonApi\Settings;
use App\Models\Setting;
use CloudCreativity\LaravelJsonApi\Http\Controllers\EloquentController;

/**
 * Class SettingsController
 *
 * @package App\Http\Controllers\Api
 */
class SettingsController extends EloquentController
{
    /**
     * SettingsController constructor.
     *
     * @param Settings\Hydrator $hydrator
     */
    public function __construct(Settings\Hydrator $hydrator)
    {
        parent::__construct(new Setting(), $hydrator);
    }
}
