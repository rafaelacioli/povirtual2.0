<?php

namespace Source\model;

use CoffeeCode\DataLayer\DataLayer;

class Entity extends DataLayer
{
    public function __construct()
    {
        parent::__construct("entidades", ["nome","latitude","longitude"]);
    }

    public function unitType($id)
    {
        $t = (new UnitType())->findById($id);
        return $t->nome;
    }
}