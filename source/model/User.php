<?php

namespace Source\Model;

use CoffeeCode\DataLayer\DataLayer;

class User extends DataLayer
{
    public function __construct()
    {
        parent::__construct("usuarios", ["nome", "email", "senha"], );
    }
}