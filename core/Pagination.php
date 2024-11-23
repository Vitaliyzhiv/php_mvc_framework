<?php

namespace PHPFramework;

class Pagination
{

    // защищенное свойство для подсчета количества страниц
    protected int $countPages;
    // защищенное свойство для получения текущей страницы
    protected int $currentPage;
    // ссылка для пагинации
    protected string $uri;

    public function __construct(
        protected int $perPage = 3,
        protected int $totalRecords = 1,
        // параметр который определяет сколько показывать страниц слева и сколько справа
        protected int $midSize = 2,
        // максимальное количество страниц
        protected int $maxPages = 7,
        // защищенное свойство для загрузки шаблона пагинации
        protected string $tpl = 'pagination/base',

    ) {
        //  записываем результат работы метода getCountPages    
        $this->countPages = $this->getCountPages();
        // записываем результат работы метода getCurrentPage
        $this->currentPage = $this->getCurrentPage();
        // вызываем метод для формирования строки запроса
        $this->uri = $this->getParams();
        // вызываем метод для подсчета количества страниц для отображения
        $this->midSize = $this->getMidSize();

    }

    //  функция для подсчета количества страниц
    protected function getCountPages(): int
    {
        return (int)ceil($this->totalRecords / $this->perPage) ?: 1;
    }

    //  функция для получения текущей страницы
    protected function getCurrentPage(): int
    {
        $page = (int)request()->get('page', 1);
        // выдаем 404 ошибку если такая страница не найдена
        if ($page < 1 || $page > $this->countPages) {
            abort('Page not found', 404);
        }
        // возвращаем текущую страницу
        return $page;
    }

    // получение параметров url
    protected function getParams()
    {
        //  получаем url из класса request
        $url = request()->uri;
        // разбиваем url на части с помощью функции parse_url
        //  parse_url - разбивает url на составляющие (scheme, host, port, user, pass, path, query, fragment)
        $url = parse_url($url);
        // формируем uri 
        //  uri - это путь до скрипта, например /user/index
        $uri = $url['path'];
        //  проверка существует ли в url параметр
        if (!empty($url['query']) && !in_array($url['query'], ['&'])) {
            // parse_str - разбирает строку запроса в массив переменных
            // пример: parse_str('name=John&age=28', $params)
            // результат: $params = ['name' => 'John', 'age' => 28]
            parse_str($url['query'], $params);
            // проверка существует ли в url параметр page
            if (isset($params['page'])) {
                // удаляем параметр page
                unset($params['page']);
            }
            // если массив параметров не пустой формируем  строку запроса $uri
            if (!empty($params)) {
                // функция http_build_query собирает массив параметров в строку запроса
                //  пример: http_build_query(['name' => 'John', 'age' => 28])
                //          // результат: "name=John&age=28"
                $uri .= '?' . http_build_query($params);

            }
        } 
        // возвращаем uri
        return $uri;
    }

    // функция getMidSize которая возвращает количество страниц для отображения в середине пагинации
    protected function getMidSize() {
        // проверяем, если общее количество страниц меньше или равно максимальному количеству страниц
        // если да, то возвращаем общее количество страниц, так как они все могут быть показаны
        // если нет, возвращаем midSize, что указывает сколько страниц будет показано с каждой стороны от текущей страницы
        return ($this->countPages <= $this->maxPages) ? $this->countPages : $this->midSize;
    }

    // функция для формирования offset для пагинации
    // offset - это смещение, которое указывает на начальную позицию для выборки данных из базы данных
    // пример:
    // если currentPage = 2, perPage = 10, то offset = (2 - 1) * 10 = 10
    public function getOffset(): int {
        // вычисляем смещение
        return ($this->currentPage - 1) * $this->perPage;
    }


}
