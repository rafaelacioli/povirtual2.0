<?php

const DATA_LAYER_CONFIG = [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "pov",
    "username" => "mesatatica",
    "passwd" => "povirtual2023",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
];

// CONFIGURAÇÃO PARA O DB POSTGRES
//const DATA_LAYER_CONFIG = [
//    "driver" => "pgsql",
//    "host" => "localhost",
//    "port" => "5432",
//    "dbname" => "datalayer",
//    "username" => "postgres",
//    "passwd" => "",
//    "options" => [
//        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
//        PDO::ATTR_CASE => PDO::CASE_NATURAL
//    ],
//];

// define("URL_BASE", "http://192.168.0.103/pov");