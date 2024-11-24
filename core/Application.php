<?php

namespace PHPFramework;

// Import recomended namespace for db connect creation
use Illuminate\Database\Capsule\Manager as Capsule;

class Application
{

    protected string $uri;
    public Request $request;
    public Response $response;
    public Router $router;
    public View $view;
    public Session $session;

    // экземпляр класса Cache
    public Cache $cache;
    // экземпляр класса Database
    public Database $db;

    public static Application $app;

    // создаем массив контейнер для хранения данных
    protected array $container = [];

    public function __construct()
    {
        self::$app = $this;
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->request = new Request($this->uri);
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View(LAYOUT);
        $this->session = new Session();
        $this->generateCsrfToken();
        // подключение к бд с помощью библиотеки Illuminate(Laravel)
        // $this->setDbConnection();
        // подключение к бд с помощью  нашего класса с бд
        $this->db = new Database();
        // свойство которое будет хранить в себе обьект класса Cache (инициализация класса Cache)
        $this->cache = new Cache();
    }

    public function run(): void
    {
        // удаляем кеш страницы при перезагрузке страницы,
        // пример использования метода remove, который удалит кеш по ключу 
        // $this->cache->remove('/users');
        // получаем страницу из кеша
        // $page = $this->cache->get($this->request->rawUri);

        // // если страница в кеше найдена
        // if (!$page) {
        //     //  запрашиваем данные
        //     $page = $this->router->dispatch();
        //     // сохраняем страницу в кеше
        //     $this->cache->set($this->request->rawUri, $page, 60); // 60 секунд  
        // }
        // echo $page;
        echo $this->router->dispatch();
    }

    /**
     * Generates a CSRF token and stores it in the session if it does not already exist.
     * This token is used to protect against Cross-Site Request Forgery attacks.
     */
    public function generateCsrfToken(): void
    {
        // Check if the session does not already have a CSRF token
        if (!session()->has('csrf_token')) {
            // Generate a new CSRF token using a secure random bytes generator
            // Convert the bytes to a hexadecimal representation
            $csrfToken = bin2hex(random_bytes(32));
            // Store the generated token in the session under the key 'csrf_token'
            session()->set('csrf_token', $csrfToken);
        }
    }

    // метод для добавления чего то в контейнер
    public function set($key, $value) {
        $this->container[$key] = $value;
    }

    // метод для получения чего то из контейнера
    public function get($key, $default = null) {
        return $this->container[$key] ?? $default;
    }
}
