<?php

namespace App\Events;

use App\Models\BaseModel;

abstract class EventAbstract
{
    /**
     * @var BaseModel
     */
    protected $model;

    public function getModel()
    {
        return $this->model;
    }

}
