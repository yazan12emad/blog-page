<?php

namespace app\core;




class Router
{
    // Point the routes to the protected app/controllers directory
    private array $routes = [];


    public function __construct()
    {
        $this->routes = require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'routes.php';
    }


    public function slug($url): string
    {
        $url = str_replace(' ', '-', $url);
        $url = str_replace('%20', '-', $url);
        $url = preg_replace('/-+/', '-', $url);
        return trim($url, '-');

    }


    public function route(): void
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = $this->slug($url);

        if (array_key_exists($url, $this->routes)) {
            [$controller, $method] = $this->routes[$url];
            $controllerInstance = new $controller();
            $result = $controllerInstance->{$method}();


        } else {
            // this code to choose the category of the blog and the full info of the blog
            foreach ($this->routes as $routePath => $route) {
                if (str_contains($routePath,')') && preg_match('#'.$routePath.'#', $url, $matches)) {
                    [$controller, $method] = $route;
                    $controllerInstance = new $controller();
                    if(isset($matches[2])){
                        // to get the blog name to show the full info of it
                        $result = $controllerInstance->{$method}($matches[2]);
                        break;
                    }
                    else {
                        // to get the category of the blogs
                        $result = $controllerInstance->{$method}($matches[1]);
                        break;
                    }
                }

            }
        }


        if (!empty($result)) {
            echo $result;

        } else {
            http_response_code(404);
            header('Location: /home');
            exit;
        }
    }
}

