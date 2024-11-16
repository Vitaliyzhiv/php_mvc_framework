<?php

namespace PHPFramework;

class Request
{

    public string $uri;

    /**
     * Constructor.
     *
     * @param string $uri The request URI without the query string.
     */
    public function __construct($uri)
    {
        $this->uri = trim(urldecode($uri), '/');
    }

    /**
     * Gets the request method.
     *
     * @return string The request method, in uppercase (e.g. GET, POST, PUT, DELETE, HEAD).
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Returns true if the request method is GET.
     *
     * @return bool Whether the request method is GET.
     */
    public function isGet(): bool
    {
        return $this->getMethod() == 'GET';
    }

    /**
     * Returns true if the request method is POST.
     *
     * @return bool Whether the request method is POST.
     */
    public function isPost(): bool
    {
        return $this->getMethod() == 'POST';
    }

    /**
     * Checks if the request is an AJAX request.
     *
     * @return bool True if the request is an AJAX request, false otherwise.
     */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Returns a GET parameter value.
     *
     * @param string $name     The parameter name.
     * @param string $default  The default value if the parameter is not set.
     *
     * @return string|null The parameter value if set, null otherwise.
     */
    public function get($name, $default = null): ?string
    {
        return $_GET[$name] ?? $default;
    }

    /**
     * Returns a POST parameter value.
     *
     * @param string $name     The parameter name.
     * @param string $default  The default value if the parameter is not set.
     *
     * @return string|null The parameter value if set, null otherwise.
     */
    public function post($name, $default = null): ?string
    {
        return $_POST[$name] ?? $default;
    }

    /**
     * Returns the path of the request without query string.
     *
     * @return string The request path.
     */
    public function getPath(): string
    {
        return $this->removeQueryString();
    }


    /**
     * Removes the query string from the request URI.
     *
     * @return string The request path without query string.
     */
    protected function removeQueryString(): string
    {
        if ($this->uri) {
            $params = explode("?", $this->uri);
            return trim($params[0], '/');
        }
        return "";
    }

    // метод для принятия и обработки данных из glob POST and GET
    public function getData(): array {
        //  заводим пустой массив для сбора данных
        $data = [];
        // проверяем способ получения данных
        $request_data = $this->isPost() ? $_POST : $_GET;
        // проходимся циклом по массиву
        foreach ($request_data as $key => $value) {
            // если значение является строкой, обрезаем пробелы
            if (is_string($value)) {
                $value = trim($value);
            }
            // добавляем ключ и значение в массив
            $data[$key] = $value;
        }
        // возвращаем массив с данными
        return $data;

    }

}