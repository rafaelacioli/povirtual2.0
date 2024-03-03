<?php

use Source\model\Entity;

require __DIR__ . "/../../vendor/autoload.php";

// Data received
$data = json_decode(file_get_contents('php://input'), true);

function changePosition($id, $lat, $long)
{
    if ($entity = (new Entity())->findById($id)) {
        $entity->latitude = $lat;
        $entity->longitude = $long;

        $entity->save();

        // return ("{id: $id, latitude: $lat, longitude: $long}");
        return (`Position of $entity->id updated`);
    }
}

$resposta = changePosition($data['id'], $data['latitude'], $data['longitude']);
