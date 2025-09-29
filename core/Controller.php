<?php

namespace app\controllers;
abstract class Controller
{

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function redirect($url)
    {
        header('location: ' . $url);
        exit;
    }


    public function render(string $string, array $array = [])
    {
        ob_start();
        extract($array);
        require_once ROOT_PATH . '/views/' . $string . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function partialRender(string $string, array $array = [])
    {
        ob_start();
        extract($array);
        require_once ROOT_PATH . '/views/' . $string . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}