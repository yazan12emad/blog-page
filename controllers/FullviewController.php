<?php

// simplify done

namespace app\controllers;

use app\core\Controller;
use app\models\BlogModel;
use app\models\FullViewModel;

class FullViewController extends Controller
{
    private BlogModel $blogModel;

    private FullViewModel $fullViewModel;

    private  $msg;

    public function __construct()
    {

        $this->blogModel = new BlogModel();
        $this->fullViewModel = new FullViewModel();
    }

    public function showBlog($blogName , $blogId)
    {
        if($this->requireRole('guest'))
        {
            $this->redirect('login');
        }

        $blogData = $this->blogModel->getBlogById($blogId);

        if (!$blogData)
            return $this->render('FullBlog.view', ['heading' => 'there is no blog available ', 'blogData' => null]);

        $comments = $this->fullViewModel->getComments($blogId);
        $status = $this->fullViewModel->checkIfUserLikes($blogId);

        return $this->render(
            'FullBlog.view',
            ['heading' => $blogData['blog_title'], 'blogData' => $blogData,
                'status' => $status, 'comments' => $comments]);

    }

    public function addLike()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        if ($this->post('Action') == null || $this->post('blog_id') == null) {
            $this->redirect('blog');
        }

        $action = $this->post('Action');
        $blog_id = $this->post('blog_id');

        $this->jsonResponse([
            'success' => $this->fullViewModel->likeAction($action, $blog_id,  $this->msg),
            'message' => $this->msg]);


    }


    public function addComment()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $this->jsonResponse([
            'success' => $this->fullViewModel->addComment($this->post(), $this->msg),
            'message' => $this->msg]);


    }




}