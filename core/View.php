<?php

namespace PHPFramework;

class View
{

    // Шаблон
    public string $layout;
    // Вид
    public string $content  = '';

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    // рендеринг страницы
    public function render($view, $data = [], $layout = ''): string
    {
        // извлекаем данные из массива $data и записывает их в переменные , где название переменной это
        // название ключа а значение ключа записывается в переменную
        extract($data);
        // записываем путь к файлу вида в переменную
        $view_file  = VIEWS . "/{$view}.php";
        // проверяем существует ли файл ввида 
        if (is_file($view_file)) {
            // Начинаем буферизацию вывода чтобы вставить вид в нужное место шаблона
            ob_start();
            require $view_file;
            // сохраняем содержимое буфера в переменную
            $this->content = ob_get_clean();
        } else {
            // вызываем функцию abort и передаем в нее сообщение об ошибке
            abort("Not found view {$view_file}", 500);
        }

        // сравниваем является ли false $layout 
        // если $layout равно false, то нет необходимости использовать шаблон,
        // и мы просто возвращаем содержимое вида в переменную $this->content  - это и есть рендеринг страницы
        // если $layout не равно false, то мы подключаем шаблон и вставляем в него содержимое вида
        if (false === $layout) {
            return $this->content;
        }

        // записываем название файла в зависимости от того существует ли он, если нет то будет возвращен 
        //дефолтный layout
        $layout_file_name = $layout ?: $this->layout;
        // путь к файлу шаблона
        $layout_file = VIEWS . "/layouts/{$layout_file_name}.php";

        if (is_file($layout_file)) {
            ob_start();
            require_once $layout_file;
            return ob_get_clean();
        } else {
            abort("Not found layout {$layout_file}", 500);
        }

    }

    // метод для рендеринга частей (элементов страницы)
    public function renderPartial($view, $data = []): string
    {
        // извлекаем данные из массива $data и записывает их в переменные , где название переменной это
        // название ключа а значение ключа записывается в переменную
        extract($data);
        // записываем путь к файлу вида в переменную
        $view_file  = VIEWS . "/{$view}.php";
        // проверяем существует ли файл ввида 
        if (is_file($view_file)) {
            // Начинаем буферизацию вывода чтобы вставить вид в нужное место шаблона
            ob_start();
            require $view_file;
            // 
            return  ob_get_clean();
        } else {
            //   Возвращаем сообщение о том что файл не найден
            return "File {$view_file} not found";
        }
    }
}
