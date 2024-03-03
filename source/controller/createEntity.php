<?php

use Source\model\Entity;

require __DIR__ . "/../../vendor/autoload.php";

function createEntity($proprietario, $nome, $matricula, $latitude, $longitude, $altitude, $imagem)
{
    $resposta = "";

    $entity = new Entity();

    $entity->proprietario = $proprietario;
    $entity->nome = $nome;
    $entity->matricula = $matricula;
    $entity->latitude = $latitude;
    $entity->longitude = $longitude;
    $entity->altitude = $altitude;
    $entity->imagem = base64_decode($imagem);

    $entity->tipo = 2;
    $entity->azimute = 100;
    $entity->ipv4 = "172.0.0.0.";
    $entity->mascara = "255.255.255.255";
    $entity->porta = 100;
    $entity->mostrar = 1;
    $entity->descricao = "";

    if ($entity->save()) {
        return "success";
    } else {
        return "fail";
    }
}

$dados = json_decode(file_get_contents('php://input'), true);

echo createEntity($dados['proprietario'], $dados['nome'], $dados['matricula'], $dados['latitude'], $dados['longitude'], $dados['altitude'], $dados['imagem']);
