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
    'fr' => [
        'id' => 3,
        'code' => 'fr',
        'title' => 'Francais',
        'base' => 0,
    ],
    // 'es' => [
    //     'id' => 4,
    //     'code' => 'es',
    //     'title' => 'Espanol',
    //     'base' => 0,
    // ],
    // 'de' => [
    //     'id' => 5,
    //     'code' => 'de',
    //     'title' => 'Deutsch',
    //     'base' => 0,
    // ],
    // 'it' => [
    //     'id' => 6,
    //     'code' => 'it',
    //     'title' => 'Italiano',
    //     'base' => 0,
    // ]
];

const MAIL_SETTINGS = [
    'host' => 'sandbox.smtp.mailtrap.io', // smtp.gmail.com  (change to your host)
    'auth' => true,
    'username' => '09072ad3e88c15', // your_email@gmail.com (change to your mail username)
    'password' => '**********2351', // xxxx xxxx xxxx xxxx (change to your password)
    'secure' => 'tls', // ssl
    'port' => 587,
    'from_email' => '247ecd161a-3cb378@inbox.mailtrap.io', // your_email@gmail.com
    'from_name' => 'My Framework', // имя отправителя
    'is_html' => true,   // поддержка верстки 
    'charset' => 'UTF-8',
    'debug' => 0, // 0 - 4
];
