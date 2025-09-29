<?php
define('SECURE_BOOT', TRUE);
define('ROOT_PATH', __DIR__);


//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require 'core/AutoloadClass.php';

use app\core\AutoloadClass;
use app\core\Router;

AutoloadClass::register();

//$session = new Session();
$router = new Router();
$router->route();


function isUrl($value): bool
{
    // Get current path only (without query string)
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $current === $value;
}









