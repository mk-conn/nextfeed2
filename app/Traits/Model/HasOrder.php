<?php

namespace App\Traits\Model;


trait HasOrder
{

    /**
     * @param int $max
     */
    public function setOrderFromMax(int $max)
    {
        $this->setAttribute('order', $max + 1);
    }

}
