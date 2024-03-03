<?php

// function curlFile($url,$proxy_ip,$proxy_port,$loginpassw)
// {
$loginpassw = 'ciavex.seac.aux1:?mMa468call';
$proxy_ip = 'http://proxy.ciavex.avex.eb.mil.br';
$proxy_port = '8080';
$url = 'http://186.250.70.138:8080/mesatatica/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $loginpassw);
$data = curl_exec($ch);
curl_close($ch);
echo($data);

    // return $data;
// }

// $dataMesaTatica = file_get_contents('http://186.250.70.138:8080/mesatatica/');
// echo('asd');
// echo($dataMesaTatica);