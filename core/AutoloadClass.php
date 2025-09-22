<?php

namespace app\core;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}
class AutoloadClass
{

    public static function register(): void
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    private static function autoload($class): void
    {
        $relativeClass = str_replace('app\\', '', $class);
        $relativeClass = str_replace('\\', '/', $relativeClass);

        $file = __DIR__ . '/../' . $relativeClass . '.php';

        if (file_exists($file)) {
            require_once $file;
        }



    }

}
