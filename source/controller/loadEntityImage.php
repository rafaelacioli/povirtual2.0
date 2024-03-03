<?php

use Source\model\Entity;

require __DIR__ . "/../../vendor/autoload.php";

function loadEntityImage($id)
{
    if ($entity = (new Entity())->findById($id)) {
        return (base64_encode($entity->imagem));
        // return ($entity->imagem);
    }   
}

$dados = json_decode(file_get_contents('php://input'), true);

$resposta = loadEntityImage($dados['id']);

echo $resposta;