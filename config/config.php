<?php

define("ROOT", dirname(__DIR__));

// public folder
const WWW = ROOT . '/public';
const CONFIG = ROOT . '/config';
const HELPERS = ROOT . '/helpers';
const APP = ROOT . '/app';
const CORE = ROOT . '/core';
const VIEWS = APP . '/Views';
const LAYOUT = 'default';
const PATH = 'http://localhost';
const DEBUG = 1;
const ERROR_LOGS = ROOT . '/tmp/error.log';


const DB_SETTINGS = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'mvc_framework',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'port' => 3306,
    'prefix' => '',
    'options' => [
        // включаем режим отладки 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // используем ассоциативные массивы
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
];