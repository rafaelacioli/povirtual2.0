<?php

use Source\Models\Unit;

require __DIR__ . "/../../vendor/autoload.php";


// Lista as unidades cadastradas em uma tabela
function listUnitOnTable($option){
    $unidade = new Unit();
    $list = $unidade->find()->fetch(true);
    
    foreach($list as $u)
    {   
        $s = (new Unit())->findById(intval($u->subordinacao));

        if ($s)
            $sigla = $s->sigla;
        else
            $sigla = "---";

        echo("<tr>");
        echo("<td>{$u->id}</td>");
        echo("<td>{$u->nome}</td>");
        echo("<td>{$u->unitType($u->tipo)}</td>");
        echo("<td>{$u->latitude}</td>");
        echo("<td>{$u->longitude}</td>");
        echo("<td>{$sigla}</td>");

        $deleteButton = "<a href=\"../../Controller/unitdelete.php?id={$u->id}\" class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"> </i></a>";

        if ($option == 1)
            echo("<td class='text-right py-0 align-middle'>{$deleteButton}</td>");
        echo("</tr>");
    }
}

// Lista as unidades cadastradas em uma caixa de seleção tipo dropdown
function listUnitOnOption() 
{
    $unidade = new Unit();
    $list = $unidade->find()->order("nome ASC")->fetch(true);
    
    foreach($list as $u)
    {   
        echo ("<option value=\"{$u->id}\">{$u->nome}</option>");
    }
}