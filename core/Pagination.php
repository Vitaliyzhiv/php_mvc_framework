<?php

namespace PHPFramework;

// класс Pagination 
class Pagination
{

    // защищенное свойство для подсчета количества страниц
    protected int $countPages;
    // защищенное свойство для получения текущей страницы
    protected int $currentPage;
    // ссылка для пагинации
    protected string $uri;

    public function __construct(
        protected int $totalRecords,
        protected int $perPage = PAGINATION_SETTINGS['perPage'],
        // параметр который определяет сколько показывать страниц слева и сколько справа
        protected int $midSize = PAGINATION_SETTINGS['midSize'],
        // максимальное количество страниц
        protected int $maxPages = PAGINATION_SETTINGS['maxPages'],
        // защищенное свойство для загрузки шаблона пагинации
        protected string $tpl = PAGINATION_SETTINGS['tpl'],

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
    protected function getMidSize()
    {
        // проверяем, если общее количество страниц меньше или равно максимальному количеству страниц
        // если да, то возвращаем общее количество страниц, так как они все могут быть показаны
        // если нет, возвращаем midSize, что указывает сколько страниц будет показано с каждой стороны от текущей страницы
        return ($this->countPages <= $this->maxPages) ? $this->countPages : $this->midSize;
    }

    // функция для формирования offset для пагинации
    // offset - это смещение, которое указывает на начальную позицию для выборки данных из базы данных
    // пример:
    // если currentPage = 2, perPage = 10, то offset = (2 - 1) * 10 = 10
    public function getOffset(): int
    {
        // вычисляем смещение
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getHtml()
    {

        // переменная для формирования шага назад, изначально пустая строка, чтобы не было ошибок если мы на первой странице.
        $back = '';
        // переменная для формирования шага вперед, изначально пустая строка, чтобы не было ошибок если мы на последней странице.
        $forward = '';
        // начальная страница
        $first_page = '';
        // последняя страница
        $last_page = '';
        // количество страниц по бокам слева
        $pages_left = [];
        // количество страниц по бокам справа
        $pages_right = [];
        // текущая страница
        $current_page = $this->currentPage;

        // если мы находимся дальше первой страницы, то заполняем $back следующим образом:
        // от текущей страницы отнимаем 1.
        if ($current_page > 1) {
            $back = $this->getLink($this->currentPage - 1);
        }

        // если мы находимся дальше последней страницы, то заполняем $forward следующим образом:
        // от текущей страницы прибавляем 1.
        if ($current_page < $this->countPages) {
            $forward = $this->getLink($this->currentPage + 1);
        }

        // если текушая страница больше mid_size + 1 то заполняем $first_page следующим образом:
        //  Пример:
        // mid-size = 3 , currentPage = 6
        //   слева тогда это будет выглядеть вот так first_page, 3, 4, 5, 6
        if ($current_page > $this->midSize + 1) {
            $first_page = $this->getLink(1);
        }

        // если текушая страница меньше чем общее количество страниц - $midsize то выводим ссылку $last_page
        if ($current_page < ($this->countPages - $this->midSize)) {
            $last_page = $this->getLink($this->countPages);
        }

        // формируем циклом правильное количество страниц слева , чтобы не было ситуаций вроде -2 -1 1 2  3
        for ($i = $this->midSize; $i > 0; $i--) {
            // если текущая страница - $i > 0 , то формируем массив pages_left
            if ($this->currentPage - $i > 0) {
                $pages_left[] = [
                    'link' => $this->getLink($this->currentPage - $i),
                    'number' => $this->currentPage - $i,
                ];
            }
        }

        // формируем $pages_right
        for ($i = 1; $i <= $this->midSize; $i++) {
            //     // если текущая  страница + текущий $i меньше максимального количества страниц, то 
            // выводим страницу справа:
            //  например:
            //  current -> 5 , countPages = 7, 
            //  первая итерация 5 + $i(1) <= 7 - true
            //  добавляем 6 страницу справа от пятой
            //  вторая итерация 5 + 2 = 7 - true 
            //  добавляем еще и 7 страницу
            //  на третьей итерации мы ничего не добавим так как 5+3 > 7 -false
            if ($this->currentPage + $i  <= $this->countPages) {
                $pages_right[] = [
                    'link' => $this->getLink($this->currentPage + $i),
                    'number' => $this->currentPage + $i,
                ];
            }
        }

        // так как пагинация это часть вида, то возвращаем ее через renderPartial
        return view()->renderPartial($this->tpl, compact(
            'back',
            'forward',
            'first_page',
            'last_page',
            'pages_left',
            'pages_right',
            'current_page'
        ));
    }

    // метод для формирования линка страницы
    // 
    // @param int $page номер страницы
    // 
    // @return string
    public function getLink($page): string
    {

        // если мы на первой странице, то get параметр page не 
        // нужен,поэтому мы обрезаем его справа по символам '&' и '?',
        // чтобы убрать лишний get параметр page
        if ($page == 1) {
            return rtrim($this->uri, '?&');
        }

        // пристыковываем параметр page к странице если там есть какие то параметры
        // 
        // например, если $this->uri = '/users?search=John',
        // то мы должны добавить параметр page к строке, 
        // чтобы получить '/users?search=John&page=2'
        // 
        // если параметров нет, то просто добавляем параметр page 
        // к строке, чтобы получить '/users?page=2'
        if (str_contains($this->uri, '&') || (str_contains($this->uri, '?'))) {
            return "{$this->uri}&page={$page}";
        } else {
            return "{$this->uri}?page={$page}";
        }
    }

    // создаем магичесский метод __toString
    // чтобы мы могли вызывать сокращено
    // echo $pagination вместо echo $pagination->getHmtl()
    public function __toString(): string {
        return $this->getHtml();
    }
}
