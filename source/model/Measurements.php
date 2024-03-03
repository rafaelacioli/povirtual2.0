<?php

namespace Source\model;

use CoffeeCode\DataLayer\DataLayer;

class Measurements extends DataLayer
{
    public function __construct()
    {
        parent::__construct("medidas_coordenacao", []);
    }
}