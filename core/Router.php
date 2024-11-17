<?php

namespace PHPFramework;

class Router
{
    //    protected Request $request;
    //    protected Response $response;

    protected array $routes = [];
    protected array $route_params = [];

    /*public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }*/

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
            'middleware' => null,
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
        $path = $this->request->getPath();
        $route = $this->matchRoute($path);
        if (false === $route) {
            abort();
        }

        if (is_array($route['callback'])) {
            $route['callback'][0] = new $route['callback'][0];
        }

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
                            // // устанавливаем flash сообщение об ошибке
                            // session()->setFlash('error', 'Ошибка безопасности');
                            // // делаем редирект на текущую страницу
                            // response()->redirect();

                            //  другой вариант делать abort 419 как в ларавель
                            abort("Ошибка безопасности", 419);
                        }

                    }
                }
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
    public function withoutCsrfToken():self {
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

}
