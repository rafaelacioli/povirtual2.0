<?php

use Source\model\Entity;

require __DIR__ . "/../../vendor/autoload.php";

$data = json_decode(file_get_contents('php://input'), true);

function deleteCard($id)
{
    $entity = (new Entity())->findById($id);
    
    if ($entity->destroy()) {
        return 0;
    } else {
        return 1;
    }
}

$resposta = deleteCard($data['id']);

echo json_encode($resposta);