<?php 

// замеряем время выполнения скрипта
$start_framework = microtime(true);

if (PHP_MAJOR_VERSION < 8) {
   die("PHP 8 or higher is required");
}

// подключаем конфиг
require_once __DIR__ . '/../config/config.php';
// подключаем автозагрузчик классов
require_once ROOT . '/vendor/autoload.php';
require_once HELPERS . '/helpers.php';

$app = new PHPFramework\Application();

require_once CONFIG . '/routes.php';

$app->run();


// dump(app());
// dump($app);
// dump(request()->getMethod());
// dump(request()->isGet());
// dump(request()->isPost());
// // проверяем параметр page в строке
// dump(request()->get('page'));
// время выполнения скрипта
// dump("Time: " . (microtime(true) - $start_framework));