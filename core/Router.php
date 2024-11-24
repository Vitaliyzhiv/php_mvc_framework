<?php

namespace PHPFramework;

class Router
{

    protected array $routes = [];
    protected array $route_params = [];

    // вариант после пхп 8, с передачей объектов других классов прямо в конструктор
    public function __construct(
        protected Request $request,
        protected Response $response
    ) {}

    public function add($path, $callback, $method): self
    {
        $path = trim($path, '/');
        if (is_array($method)) {
            $method = array_map('strtoupper', $method);
        } else {
            $method = [strtoupper($method)];
        }

        $this->routes[] = [
            'path' => "/$path",
            'callback' => $callback,
            'middleware' => [],
            'method' => $method,
            'needCsrfToken' => true,
        ];
        return $this;
    }

    public function get($path, $callback): self
    {
        return $this->add($path, $callback, 'GET');
    }

    public function post($path, $callback): self
    {
        return $this->add($path, $callback, 'POST');
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function dispatch(): mixed
    {
        // Получаем путь из запроса, удаляя строку запроса и очищая лишние символы.
        // Метод getPath() возвращает путь запроса без параметров GET.
        $path = $this->request->getPath();

        // Пытаемся сопоставить текущий путь с доступными маршрутами.
        // Если маршрут найден, он возвращается в виде массива.
        // Если маршрут не найден, возвращается false.
        $route = $this->matchRoute($path);

        // Если маршрут не найден (возвращено false), вызываем функцию abort().
        // Обычно abort() отправляет HTTP-ответ с кодом ошибки (например, 404 Not Found).
        if (false === $route) {
            abort();
        }

        // Проверяем, является ли "callback" маршрута массивом.
        // Это используется для вызова методов контроллеров в формате [Контроллер, Метод].
        if (is_array($route['callback'])) {
            // Если callback — массив, создаем экземпляр класса контроллера.
            // Это делает первый элемент массива экземпляром класса контроллера,
            // вместо строки с его именем.
            $route['callback'][0] = new $route['callback'][0];
        }

        // Вызываем callback, связанный с маршрутом.
        // call_user_func() вызывает функцию или метод, переданный в $route['callback'].
        // Если callback — замыкание, он вызывается напрямую.
        // Если callback — массив [Класс, Метод], вызывается указанный метод.
        return call_user_func($route['callback']);
    }


    protected function matchRoute($path): mixed
    {
        foreach ($this->routes as $route) {
            if (
                preg_match("#^{$route['path']}$#", "/{$path}", $matches)
                &&
                in_array($this->request->getMethod(), $route['method'])
            ) {

                // проверять наличие csrf токена имеет смысл только в POST запросе, так как важные данные методом GET не отправляют 

                if (request()->isPost()) {
                    // если запрос требует csrf токена и метод проверки токена не прошел, то возвращаем ошибку
                    if ($route['needCsrfToken'] && !$this->checkCsrfToken()) {

                        // если данные были обработанны ajax запросом то возвращаем ошибку с помощью json_encode
                        if (request()->isAjax()) {
                            echo json_encode([
                                'status' => 'error',
                                'data' => 'Security error',
                            ]);
                            die;
                        } else {
                            //  другой вариант делать abort 419 как в ларавель
                            abort("Ошибка безопасности", 419);
                        }
                    }
                }

                // проверяем не является ли пустым  middleware для маршрута
                if ($route['middleware']) {
                    // проходим по всем middleware и вызываем их
                    foreach ($route['middleware'] as $item) {
                        // проверяем есть ли middleware в константе
                        $middleware = MIDDLEWARE[$item] ?? false;
                        if ($middleware) {
                            // создаем экземпяр класса
                            $middlewareInstance = new $middleware;
                            // вызываем метод handle в данного экземпляра класса
                            $middlewareInstance->handle();
                        }
                    }
                }
                if ($route)
                    foreach ($matches as $k => $v) {
                        if (is_string($k)) {
                            $this->route_params[$k] = $v;
                        }
                    }
                return $route;
            }
        }
        return false;
    }

    /**
     * This function allows to disable csrf token check for some routes.
     * We use array_key_last() to get the last route added to the routes array
     * and set the 'needCsrfToken' key to false.
     * This way, the route will not be checked for csrf token.
     *
     * @return self
     */
    public function withoutCsrfToken(): self
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['needCsrfToken'] = false;
        return $this;
    }

    /**
     * This function checks if the csrf token is present in the request and matches
     * the token stored in the session.
     *
     * @return bool True if the csrf token is valid, false otherwise.
     */
    public function checkCsrfToken(): bool
    {
        // Check if the csrf token is present in the request
        // as a post parameter
        if (!request()->post('csrf_token')) {
            return false;
        }

        // Check if the csrf token matches the token stored in the session
        // If it does not match, return false
        if (request()->post('csrf_token') !== session()->get('csrf_token')) {
            return false;
        }

        // If we reach this point, that means the csrf token is valid
        // so return true
        return true;
    }


    /**
     * Adds middleware to the last added route.
     *
     * This method allows you to add middleware to the last route that was added
     * to the router. This can be useful if you want to add middleware to a specific
     * route and not to all routes.
     *
     * The middleware is specified as an array of strings, each string being the
     * name of a class that implements the MiddlewareInterface.
     *
     * @param array $middleware
     * @return $this
     */
    public function middleware(array $middleware): self
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middleware'] = $middleware;
        return $this;
    }
}
