<?php

use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

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

// создаем экземпляр класса Whoops для вывода ошибок
$whoops = new Run();
// проверяем статус константы DEBUG для установки нужного режима отладки
if (DEBUG) {
   // режим разработки, выводим ошибки на экран
   $whoops->pushHandler(new PrettyPageHandler());
} else {
   // режим продакшн, выводим ошибки в лог
   $whoops->pushHandler(new CallbackHandler(function (Throwable $e) {
      // dump($e);
      // в файл лога записываем название файла где произошла ошибка, строку и само сообщение об ошибке
      error_log("File: {$e->getFile()}, Line: {$e->getLine()}, Message: {$e->getMessage()}\n", 3, ERROR_LOGS);
      // вызываем функцию abort для перенаправления на страницу ошибки
      abort("Some error", 500);
      
   }));
}
// запускаем Whoops
$whoops->register();



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