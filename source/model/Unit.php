<?php

namespace Source\model;

use CoffeeCode\DataLayer\DataLayer;
use Source\model\UnitType;

class Unit extends DataLayer
{
    public function __construct()
    {
        parent::__construct("unidades", ["nome","latitude","longitude"]);
    }

    public function unitType($id)
    {
        $t = (new UnitType())->findById($id);
        return $t->nome;
    }
}