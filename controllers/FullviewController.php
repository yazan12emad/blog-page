<?php

// simplify done

namespace app\controllers;

use app\core\Controller;
use app\core\Session;
use app\models\BlogModel;
use app\models\FullViewModel;

class FullViewController extends Controller
{
    private session $session;

    private BlogModel $blogModel;
    private FullViewModel $fullViewModel;
    private ?string $message ;

    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->blogModel = new BlogModel();
        $this->fullViewModel = new FullViewModel();
    }

    public function showBlog($blogName , $blogId)
    {
        if($this->requireRole('guest')) {
            $this->redirect('login');
        }

        $blogData = $this->blogModel->getBlogById($blogId);

        if (!$blogData)
            return $this->render('FullBlog.view', ['heading' => 'there is no blog available ', 'blogData' => null]);

        $status = $this->fullViewModel->checkIfUserLikes($blogId , $this->session->get('id'));

        return $this->render(
            'FullBlog.view', [
                'heading' => $blogData['blog_title'],
                'blogData' => $blogData,
                'status' => $status,
                'comments' => $this->fullViewModel->getComments($blogId)]) ?? null;

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
            'success' => $this->fullViewModel->likeAction($action,$this->session->get('id') , $blog_id,  $this->message),
            'message' => $this->message]);


    }


    public function addComment()
    {
        if (!$this->isPost()) {
            $this->redirect('blog');
        }

        $this->jsonResponse([
            'success' => $this->fullViewModel->addComment($this->post(),$this->session->get('id') , $this->message),
            'message' => $this->message]);


    }




}