<?php

namespace app\models;

use app\core\DataBase;
use Exception;

class FullViewModel
{
    public DataBase $DB;
    public ValidationClass $ValidationClass;

    public function __construct()
    {
        $this->ValidationClass = new ValidationClass();
        $this->DB = DataBase::getInstance();

    }

    public function likeAction($action, $blog_id, $user_id, &$msg = null): bool
    {
        try {
            if (!$this->checkIfUserLikes($blog_id, $user_id)) {

                $this->DB->query('update blog set Likes = Likes+1 WHERE blog_id =:blog_id', [
                        'blog_id' => $blog_id
                    ]
                );
                $this->DB->query('INSERT INTO likes (Blog_id,User_id ,Action) VALUES(:blog_id, :User_id ,:Action )', [
                    'blog_id' => $blog_id,
                    'User_id' => $user_id,
                    'Action' => $action

                ]);

            } else {
                $this->DB->query('update blog set Likes = Likes-1 WHERE blog_id =:blog_id', [
                        'blog_id' => $blog_id
                    ]
                );

                $this->DB->query('Delete from Likes where blog_id =:blog_id AND User_id =:User_id  ', [
                    'blog_id' => $blog_id
                    , 'User_id' => $user_id]);

            }
        }
        catch (Exception $e) {
            $msg = $e->getMessage();
            return false;
        }
        return true;

    }

    public function checkIfUserLikes($blog_id, $User_id)
    {
        if ($this->DB->query('SELECT * from likes where Blog_id =:Blog_id AND User_id =:User_id  ', [
                'Blog_id' => $blog_id
                , 'User_id' => $User_id
            ])->rowCount() !== 0)
            return 'true';
        else
            return 'false';

    }

    public function addComment($commentData, $user_id, &$msg = null)
    {
        $comment_text = $commentData['comment_body'];
        if (!$this->ValidationClass->notEmpty($comment_text, $msg)) {
            return false;
        }

        if ($this->insertComment($commentData['commented_blog_id'], $user_id, $comment_text, $commentData['parent_comment_id'], $msg))
            return true;
        else
            return false;


    }


    public function insertComment($blog_id, $user_id, $comment_text, $parent_comment_id, &$msg = null)
    {
        try {


            if (empty($parent_comment_id)) {
                if ($this->DB->query('insert into comment_system(User_id,blog_id , comment_text) VALUES(:User_id , :blog_id ,:comment_text )', [
                    'User_id' => $user_id,
                    'blog_id' => $blog_id,
                    'comment_text' => $comment_text
                ])) {
                    $msg = 'Comment inserted refresh the page to show the comment';
                    return true;
                } else {
                    $msg = 'Error insert comment';
                    return false;

                }
            }

            if ($this->DB->query('insert into comment_system(User_id,blog_id , comment_text ,parent_comment_id) VALUES(:User_id , :blog_id ,:comment_text ,:parent_comment_id) ', [
                'User_id' => $user_id,
                'blog_id' => $blog_id,
                'comment_text' => $comment_text,
                'parent_comment_id' => $parent_comment_id
            ])) {
                $msg = 'Comment inserted refresh the page to show the comment';
                return true;
            } else {
                $msg = 'Error insert comment';
                return false;

            }
        }
        catch (Exception $e) {
            $msg = $e->getMessage();
            return false;
        }

    }

    public function getComments($blog_id)
    {

        $comments = $this->DB->query('
    SELECT 
        comment_system.*,
        UsersInformation.userName
    FROM comment_system 
    INNER JOIN UsersInformation ON comment_system.User_id = UsersInformation.id
    WHERE blog_id = :blog_id 
    ORDER BY created_at DESC
', [
            'blog_id' => $blog_id
        ])->fetchAll();

        if (empty($comments)) {
            return false;
        } else
            return $comments;

    }

}
