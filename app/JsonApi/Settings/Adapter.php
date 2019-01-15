<?php

namespace App\JsonApi\Settings;

use App\JsonApi\DefaultAdapter;
use App\Models\Setting;

class Adapter extends DefaultAdapter
{
    
    const MODEL = Setting::class;
}
