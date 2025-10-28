<?php

namespace app\core;


if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

class Router
{
    // Point the routes to the protected app/controllers directory
    private array $routes = [];


    public function __construct()
    {
        $this->routes = require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'routes.php';
    }

    public function route(): void
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // check if the URL is existed or not
        if (array_key_exists($url, $this->routes)) {
            [$controller, $method] = $this->routes[$url];
            $AuthController = new $controller();
            $result = $AuthController->{$method}();

            if (!empty($result)) {
                echo $result;

            }

//            require $this->routes[$url];
        } else {
            // A request for an invalid route results in a 404 error
            http_response_code(404);
            // Optionally, you can redirect to a specific page or show a custom error page
            header('location: /home');
        }
    }
}
