<?php
//namespace app\controllers;
//use app\core\Session;
//
//
//class Navbar
//{
//    protected $session;
//    public  function __construct()
//    {
//        $this->session = new Session();
//    }
//    public  function check(): array
//    {
//
//        if($this->session->get("imgBefore"))
//            $navImg = $this->session->get("imgBefore");
//
//        // Logout
//        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
//            $this->session->destroy();
//            header("Location: /signup.php");
//            exit;
//        }
//
//        if ($this->session->has('id')) {
//            return [
//                'logged_in' => true,
//                'userName'  => $this->session->get('userName') ?? 'Guest'
//            ];
//        }
//
//        return [
//            'logged_in' => false
//        ];
//    }
//
////    public function navImg(){
////        if($this->session->get("imgBefore"))
////            $navImg = $this->session->get("imgBefore");
////        else
////            $navImg ='/public/default.png';
////        return $navImg;
////
////    }
//}

