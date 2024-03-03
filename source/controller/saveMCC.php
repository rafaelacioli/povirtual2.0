<?php

use Source\model\Measurements;

require __DIR__ . "/../../vendor/autoload.php";

$data = json_decode(file_get_contents('php://input'), true);
$resposta = "";


function savePolyline($proprietario, $coordenadas)
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->tipo_medida_coordenacao = 1;
    $mcc->coordenadas = $coordenadas;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "error";
    }
}

function savePolygon($proprietario, $nome, $vertices, $raio, $cor, $latitude, $longitude, $azimute, $altura, $altitude, $fixarSolo, $coordenadas)
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->nome = $nome;
    $mcc->tipo_medida_coordenacao = 2;
    $mcc->vertices = $vertices;
    $mcc->raio = $raio;
    $mcc->cor = $cor;
    $mcc->latitude = $latitude;
    $mcc->longitude = $longitude;
    $mcc->azimute = $azimute;
    $mcc->altura = $altura;
    $mcc->altitude = $altitude;
    $mcc->fixar_solo = $fixarSolo;
    $mcc->coordenadas = $coordenadas;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "error";
    }
}

function saveFreeLine($proprietario, $coordenadas)
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->tipo_medida_coordenacao = 3;
    $mcc->coordenadas = $coordenadas;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "Linha Livre deu Ruim!";
    }
}
function saveDashedPolyline($proprietario, $coordenadas)
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->tipo_medida_coordenacao = 4;
    $mcc->coordenadas = $coordenadas;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "error";
    }
}

function savePolygon2D($proprietario, $coordenadas)
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->tipo_medida_coordenacao = 6;
    $mcc->coordenadas = $coordenadas;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "error";
    }
}

function saveLabel($proprietario, $latitude, $longitude, $altitude, $descricao, $tamanho_texto) 
{
    $mcc = new Measurements();

    $mcc->proprietario = $proprietario;
    $mcc->tipo_medida_coordenacao = 7;
    $mcc->latitude = $latitude;
    $mcc->longitude = $longitude;
    $mcc->altitude = $altitude;
    $mcc->tamanho_texto = $tamanho_texto;
    $mcc->descricao = $descricao;

    if ($mcc->save()) {
        return "ok";
    } else {
        return "error";
    }
}

switch ($data['tipo_medida_coordenacao']) {
    case 1:
        $resposta = savePolyline($data['proprietario'], $data['coordenadas']);
        break;
    case 2:
        $resposta = savePolygon($data['proprietario'], $data['nome'], $data['vertices'], $data['raio'], $data['cor'], $data['latitude'], $data['longitude'], $data['azimute'], $data['altura'], $data['altitude'], $data['fixarSolo'], $data['coordenadas']);
        break;
    case 3:
        $resposta = saveFreeLine($data['proprietario'], $data['coordenadas']);
        break;
    case 4:
        $resposta = saveDashedPolyline($data['proprietario'], $data['coordenadas']);
        break;

    case 6:
        $resposta = savePolygon2D($data['proprietario'], $data['coordenadas']);
        break;
    case 7:
        $resposta = saveLabel($data['proprietario'], $data['latitude'], $data['longitude'], $data['altitude'], $data['descricao'], $data['tamanho_texto']);
        break;
    default:
        break;
}

echo json_encode($resposta);
