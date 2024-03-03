<?php

use Source\model\Measurements;

require __DIR__ . "/../../vendor/autoload.php";

$data = json_decode(file_get_contents('php://input'), true);

function deleteMCC($id)
{
    $mcc = (new Measurements())->findById($id);
    
    if ($mcc->destroy()) {
        return 0;
    } else {
        return 1;
    }
}

$resposta = deleteMCC($data['id']);

echo json_encode($resposta);