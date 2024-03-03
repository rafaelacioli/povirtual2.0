<?php

namespace Source\Model;

use CoffeeCode\DataLayer\DataLayer;

class UnitType extends DataLayer
{
    public function __construct()
    {
        parent::__construct("tipo_unidade",["nome"]);
    }
}