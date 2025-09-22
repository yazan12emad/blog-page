<?php
define('SECURE_BOOT', TRUE);

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require 'core/AutoloadClass.php';

use app\core\AutoloadClass;
use app\core\Router;
use app\core\Session;
use app\controllers\Navbar;

AutoloadClass::register();
$session = new Session();
$Router = new Router();

$Router->route();

function navbar()
{
    $navData = new Navbar();
    $navData = $navData->check();
    return $navData;

}


function isUrl($value): bool
{
    // Get current path only (without query string)
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    return $current === $value;
}







