<?php

use Source\model\Entity;

require __DIR__ . "/../../vendor/autoload.php";

function loadEntities()
{
    $data = [];

    if ($entities = (new Entity())->find()->fetch(true)) {
        foreach ($entities as $entity) {
            $id = $entity->id;
            $nome = $entity->nome;

            $entityData = [
                'id' => $id,
                'nome' => $nome,
            ];

            array_push($data, $entityData);
        }

        return $data;
    }
}

$resposta = json_encode(loadEntities());

// var_dump($resposta);
echo $resposta;