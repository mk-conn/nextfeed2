<?php


namespace App\Models;


abstract class BaseAction
{

    /**
     * @var array*
     */
    public $result = [];

    /**
     * @var array
     */
    protected $params = [];


    /**
     * @return array
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = array_dot($params);
    }
}
