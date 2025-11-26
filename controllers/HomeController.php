<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;

class HomeController extends Controller
{
    private session $session;
    public function __construct(){

        $this->session = Session::getInstance();

    }
    public function showHome(): string
    {
        if ($this->session->isGuest())
            return $this->render('home.view', ['heading' => 'home']);

        else
            if ($this->session->isAdmin())
                return $this->render('home.view', ['heading' => 'home']);
            else
                return $this->render('home.view', ['heading' => 'home']);

    }

    public function showBlog(){

    }


}