<?php
// This file for check if the user do :
// post any form
// redirect if the user search for files un access files
//by ob_start print the return form the controllers print it with view


namespace app\core;
abstract class Controller
{

    public function isPost() : bool
    {

        return $_SERVER['REQUEST_METHOD'] === 'POST';

    }

    public function redirect($url) : void
    {
        header('location: ' . $url);
        exit;
    }



    public function render(string $string, array $array = []): string
    {
        ob_start();
        extract($array);
        require_once ROOT_PATH . '/views/' . $string . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function partialRender(string $string, array $array = []):string
    {
        ob_start();
        extract($array);
        require_once ROOT_PATH . '/views/' . $string . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}