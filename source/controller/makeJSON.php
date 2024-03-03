<?php
require __DIR__ . "/../../vendor/autoload.php";

use Source\Models\Unit;

function getData()
{
    $features = [];
    $unidades = new Unit();
    $listaUnidades = $unidades->find()->fetch(true);

    foreach ($listaUnidades as $unidade) {
        $features[] =
        [
            'id' => intval($unidade->id),
            'codom' => intval($unidade->codom),
            'nome' => $unidade->nome,
            'sigla' => $unidade->sigla,
            'tipo' => $unidade->tipo,
            'coordenadas' => [
                'latitude' => floatval($unidade->latitude),
                'longitude' => floatval($unidade->longitude),
                'altitude' => floatval($unidade->altitude)
            ],
        ];
    }
    return json_encode($features);
}

// $fileName = "unidades-" . date('d-m-Y') . "-" . date('H:i:s') . ".json";
$fileName = "latest.json";
file_put_contents($fileName, getData());

header("location:{$fileName}");
?>