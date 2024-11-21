<?php

/** @var \PHPFramework\Application $app */

use App\Controllers\HomeController;
use App\Controllers\UserController;

// создаем ассоциативный массив константу middleware для соотношения к какому классу относится конкретный 
// ключ в $routes['middleware']
const MIDDLEWARE = [
    'auth' => \PHPFramework\Middleware\Auth::class
];


$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth']);
$app->router->get('/register', [UserController::class, 'register']);
// получение данных из формы при регистрации
$app->router->post('/register', [UserController::class, 'store']);
$app->router->get('/login', [UserController::class, 'login']);



// dump($app->router->getRoutes());