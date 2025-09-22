<?php
namespace app\core;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}
class Router
{
    // Point the routes to the protected app/controllers directory
    private array $routes = [
            "/home.php" => __DIR__ . "/../controllers/home.php",
            "/blogs.php" => __DIR__ . "/../controllers/blogs.php",
            "/categories.php" => __DIR__ . "/../controllers/categories.php",
            "/signUp.php" => __DIR__ . "/../controllers/signUp.php",
            "/logIn.php" => __DIR__ . "/../controllers/logIn.php",
            "/profilePage.php" => __DIR__ . "/../controllers/profilePage.php",
            "/logout.php" => __DIR__ . "/../controllers/logout.php",
            "/forgetPassword.php" => __DIR__ . "/../controllers/forgetPassword.php",
            '/submitNewPassword.php' => __DIR__ . "/../controllers/submitNewPassword.php",

    ];

    public function route(): void
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (array_key_exists($url, $this->routes)) {
            require $this->routes[$url];
        } else {
            // A request for an invalid route results in a 404 error
            http_response_code(404);
            // Optionally, you can redirect to a specific page or show a custom error page
            echo "404 Page Not Found";
            header('location: /home.php');
        }
    }
}
