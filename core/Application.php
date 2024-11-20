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
    // экземпляр класса Database
    public Database $db;

    public static Application $app;

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
    }

    public function run(): void
    {
        echo $this->router->dispatch();
    }

    /**
     * Generates a CSRF token and stores it in the session if it does not already exist.
     * This token is used to protect against Cross-Site Request Forgery attacks.
     */
    public function generateCsrfToken() :void {
        // Check if the session does not already have a CSRF token
        if (!session()->has('csrf_token')) {
            // Generate a new CSRF token using a secure random bytes generator
            // Convert the bytes to a hexadecimal representation
            $csrfToken = bin2hex(random_bytes(32));
            // Store the generated token in the session under the key 'csrf_token'
            session()->set('csrf_token', $csrfToken);
        }
    }


    public function setDbConnection(){
        $capsule = new Capsule();
        $capsule->addConnection(DB_SETTINGS);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

