<?php

namespace app\controllers;

use app\core\Controller;
use app\models\HomeModel;


class HomeController extends Controller
{
    private  HomeModel $HomeModel;
    public function __construct() {
        $this->HomeModel = new HomeModel();
    }

    public function showHome(): string
    {
        $blogs = $this->HomeModel->getBlogs();
        return $this->render('home.view', [
            'heading' => 'home',
            'blogs'=> $blogs,
        ]);
    }

}