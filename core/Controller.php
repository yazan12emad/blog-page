<?php
// This file for check if the user do :
// post any form
// redirect if the user search for files un access files
//by ob_start print the return form the controllers print it with view


namespace app\core;

use app\core\Session;
use JetBrains\PhpStorm\NoReturn;


abstract class Controller
{
    private $session;



    public function isPost() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    #[NoReturn]
    public function redirect($url) : void
    {
        header('Location: /'.$url );
        exit;
    }

    public function navData():array{
        $this->session = Session::getInstance();
        if($this->session->isGuest())
        $navData = [
            'logIn' => false ,
            'role' =>'guest'
        ];

        else if($this->session->isAdmin())
             $navData =
                 [
                 'logIn' => true ,
                 'role' =>'admin',
                'admin_id' => $this->session->get('id'),
                 ];
        else $navData=
            [
            'logIn' => true ,
            'role' =>'user' ,
            'user_id' => $this->session->get('id'),
            ];

        return $navData;

    }

    public function render(string $string, array $array = []): string
    {
        ob_start();
        $array = array_merge($array, ['navData'=>$this->navData()]);
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