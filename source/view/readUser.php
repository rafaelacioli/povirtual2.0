<?php

use Source\Models\User;

require __DIR__ . "/../../vendor/autoload.php";

$user = new User();

$userList = $user->find()->fetch(true);


foreach ($userList as $user)
{
    $editButton = '<a href="" class="btn btn-success btn-sm"><i class="fas fa-edit"> </i></a>';
    $deleteButton = '<a href="" href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"> </i></a>';

    echo ("<tr>");
    echo("<td>{$user->id}</td>");    
    echo("<td>{$user->nome}</td>");
    echo("<td>{$user->classe}</td>");
    echo("<td>{$user->usuario_web}</td>");
    echo("<td>{$user->email}</td>");
    echo("<td>{$user->status}</td>");
    echo("<td>{$user->posto_graduacao}</td>");
    echo("<td>{$user->perfil}</td>");
    echo("<td>{$user->created_at}</td>");
    echo("<td>{$user->updated_at}</td>");
    echo("<td class='text-right py-0 align-middle'>{$editButton} {$deleteButton}</td>");
    echo ("</tr>");
}