<?php

namespace app\controllers;

use app\core\Controller;
use app\models\BlogModel;
use app\core\Session;
use app\models\FullViewModel;

class FullViewController extends Controller
{
    private BlogModel $blogModel;

    private session $Session;

    private FullViewModel $FullViewModel;

    private $msg = [];

    public function __construct()
    {
        $this->Session = Session::getInstance();
        $this->blogModel = new BlogModel();
        $this->FullViewModel = new FullViewModel();
    }

    public function showBlog($blogId)
    {
        if ($this->Session->userRole() == 'guest') {
            $this->redirect('login');
            return false;
        } else {
            $blogData = $this->blogModel->getBlogById($blogId);
            $comments = $this->FullViewModel->getComments($blogId);
            $status = $this->FullViewModel->checkIfUserLikes($blogId, $this->Session->get('id'));

            if ($blogData)
                return $this->render('FullBlog.view', ['heading' => $blogData['blog_title'], 'blogData' => $blogData, 'status' => $status, 'comments' => $comments]);
            else
                return $this->render('FullBlog.view', ['heading' => 'there is no blog available ', 'blogData' => FALSE]);
        }
    }

    public function addLike()
    {
        if ($this->isPost()) {

            header('Content-Type: application/json');
            return json_encode([
                'success' => $this->FullViewModel->likeAction($_POST['Action'], $_POST['blog_id'], $this->Session->get('id'), $this->msg),
                'message' => $this->msg,
            ]);

        }

    }

    public function addComment()
    {
        if ($this->isPost()) {

            header('Content-Type: application/json');
            return json_encode([
                'success' => $this->FullViewModel->addComment($_POST, $this->Session->get('id'), $this->msg),
                'message' => $this->msg,
            ]);

        }


    }


}