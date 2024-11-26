<?php

/** @var \PHPFramework\Application $app */

use App\Controllers\HomeController;
use App\Controllers\UserController;

// создаем ассоциативный массив константу middleware для соотношения к какому классу относится конкретный 
// ключ в $routes['middleware']
const MIDDLEWARE = [
    'auth' => \PHPFramework\Middleware\Auth::class
];


// Специфичный маршрут для панели управления
$app->router->get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth']);

// Маршрут для регистрации
$app->router->get('/register', [UserController::class, 'register']);
// Обработка данных из формы регистрации
$app->router->post('/register', [UserController::class, 'store']);

// Маршрут для входа
$app->router->get('/login', [UserController::class, 'login']);

// Список пользователей
$app->router->get('/users', [UserController::class, 'index']);

// Динамический маршрут для поста с параметром "slug"
// Использует регулярное выражение для захвата сегмента URL
$app->router->get('/post/(?P<slug>[a-z0-9-]+)', function () {
    dump(app()->router->route_params);
    return "Post " . get_route_param('slug2'); // Пример вызова динамического параметра
});

// Главная страница — более общий маршрут, который подходит практически для любого запроса
// Должен быть внизу, чтобы не перехватить все запросы, предназначенные для других маршрутов.
$app->router->get('/', [HomeController::class, 'index']);



// dump($app->router->getRoutes());
