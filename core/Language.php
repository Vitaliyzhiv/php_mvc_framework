<?php

namespace PHPFramework;

class Language
{

    // свойство для хранения данных какого то языка
    //  static значит что свойство принадлежит самому классу, а не обьекту класса 
    // Используется, если значение нужно хранить в одном месте, независимо от количества объектов класса.
    public static array $lang_data = [];

    // массив переводных фраз которые относятся к шаблону/массив общих переводных фраз
    protected static array $lang_layout = [];

    // массив переводных фраз которые относятся к виду
    protected static array $lang_view = [];

    // метод для загрузки языковых фраз
    public static function load($route)
    {
        // получаем код языка из  cвойства контейнер, класса Application
        $code = app()->get('lang')['code'];

        // берем файл шаблона исходя из кода языка
        $lang_layout = APP . "/languages/$code.php";

        $lang_view = '';
        if (is_array($route)) {
            // если массив не является callback функцией closure, то делем explode обработчика по \
            // например:
            // $app->router->get('/users', [UserController::class, 'index']);
            // App/Controllers/UserController
            // нас интересует последний сегмент UserController
            $controller_segments = explode('\\', $route[0]);

            // берем последний сегмент, который является именем контроллера
            $controller_name = end($controller_segments);

            // далее с помощи методов strtolower и str_replace 
            // приводим все к нижнему регистру и заменяем слово Controller на пустую строку
            // чтобы в итоге получить название папки для языкового файла, так как согласно именований
            // у нас название папки для переводов совпадает с первой частью названия контроллера
            $folder_name = strtolower(str_replace('Controller', '', $controller_name));
            $file_name = "$route[1].php";

            // далее получаем путь к нужному файлу для перевода
            // чтобы все работало, название файла должно совпадать  с названием метода обработчика
            // Получаем путь к файлу перевода
            $lang_view = APP . "/Languages/$code/$folder_name/$file_name";
        }

        // проверяем наличие файлов 
        if (file_exists($lang_layout)) {
            // если файл существует, то загружаем его в свойство класса
            self::$lang_layout = require_once $lang_layout;
        }

        if (file_exists($lang_view) && $lang_view) {
            // если файл существует, то загружаем его в свойство класса
            self::$lang_view = require_once $lang_view;
        }

        // объединяем массивы переводных фраз
        self::$lang_data = array_merge(self::$lang_layout, self::$lang_view);
    }

    // метод для получения данных из массива
    public static function get($key) {
        return self::$lang_data[$key]?? $key;
    }
}
