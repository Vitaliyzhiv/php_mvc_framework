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
const CACHE = ROOT . '/tmp/cache';


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

const PAGINATION_SETTINGS = [
    'perPage' => 3,
    'midSize' => 2,
    'maxPages' => 7,
    'tpl' => 'pagination/base',
];

const MULTILANGS = 1;

const LANGS = [
    'ru' => [
        'id' => 1,
        'code' => 'ru',
        'title' => 'Русский',
        'base' => 1,
    ],
    'en' => [
        'id' => 2,
        'code' => 'en',
        'title' => 'English',
        'base' => 0,
    ],
    'es' => [
        'id' => 3,
        'code' => 'es',
        'title' => 'Espanol',
        'base' => 0,
    ],
    'fr' => [
        'id' => 4,
        'code' => 'fr',
        'title' => 'Francais',
        'base' => 0,
    ],
    'de' => [
        'id' => 5,
        'code' => 'de',
        'title' => 'Deutsch',
        'base' => 0,
    ],
    'it' => [
        'id' => 6,
        'code' => 'it',
        'title' => 'Italiano',
        'base' => 0,
    ]
];