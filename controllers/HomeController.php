<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\HomeModel;


class HomeController extends Controller
{
    private session $session;
    private  HomeModel $HomeModel;


    public function __construct(){

        $this->session = Session::getInstance();
        $this->HomeModel = new HomeModel();

    }
    public function showHome(): string
    {
        $blogs = $this->HomeModel->getBlogs();
//        if ($this->session->isGuest() == )
            return $this->render('home.view', ['heading' => 'home' ,'blogs'=> $blogs ]);

//        else
//            if ($this->session->isGuest())
//                return $this->render('home.view', ['heading' => 'home' ,'blogs'=> $blogs ]);
//            else
//                return $this->render('home.view', ['heading' => 'home' ,'blogs'=> $blogs ]);

    }




}