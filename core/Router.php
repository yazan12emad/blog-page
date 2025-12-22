<?php

namespace app\core;

use http\Header;

class Router
{
    // Point the routes to the protected app/controllers directory
    private array $routes;


    public function __construct()
    {
        $this->routes = require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'routes.php';
    }

    public function slug($url): string
    {
        $url = preg_replace('/[^A-Za-z0-9\/]+/', '-', $url);

        $url = preg_replace('/-+/', '-', $url);

        return trim($url, '-');
    }

    public function route(): void
    {
        $rawUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (preg_match('/[^A-Za-z0-9\/\-]+/', $rawUrl, $match)) {

            $cleanUrl = $this->slug($rawUrl);
            header('Location: ' . $cleanUrl);

        }
        $cleanUrl = $this->slug($rawUrl);


        if (array_key_exists($cleanUrl, $this->routes)) {
            [$controller, $method] = $this->routes[$cleanUrl];

            $controllerClass = new $controller();

            $result = $controllerClass->{$method}();

        } else {

            foreach ($this->routes as $route => [$controllerClass, $method]) {
                $regex = "#^{$route}$#";

                if (preg_match($regex, $cleanUrl, $matches)) {
                    array_shift($matches);
                    $controller = new $controllerClass();
                    $result = $controller->{$method}(...$matches);

                    break;
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

