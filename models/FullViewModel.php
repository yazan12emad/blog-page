<?php

namespace app\models;

use app\core\DataBase;
use app\core\Session;
use Exception;
use PDO;

class FullViewModel
{
    public DataBase $dataBase;
    public validationClass $validationClass;


    public function __construct()
    {
        $this->validationClass = new validationClass();
        $this->dataBase = DataBase::getInstance();
    }


    public function likeAction($action, $user_id, $blog_id, &$message = null): bool
    {
        try {
            if (!$this->checkIfUserLikes($blog_id, $user_id)) {

                $this->dataBase->query('update blog set Likes = Likes+1 WHERE blog_id =:blog_id', [
                        'blog_id' => $blog_id
                    ]
                );
                $this->dataBase->query('INSERT INTO likes (Blog_id,User_id ,Action) VALUES(:blog_id, :User_id ,:Action )', [
                    'blog_id' => $blog_id,
                    'User_id' => $user_id,
                    'Action' => $action

                ]);
                $message = 'like added ';
                return true;

            }
            $this->dataBase->query('update blog set Likes = Likes-1 WHERE blog_id =:blog_id', [
                    'blog_id' => $blog_id
                ]
            );

            $this->dataBase->query('Delete from Likes where blog_id =:blog_id AND User_id =:User_id  ', [
                'blog_id' => $blog_id
                , 'User_id' => $user_id]);

            $message = 'like removed ';
            return true;

        } catch (Exception $e) {
            $message = 'Error while adding like please try again later';
            return false;
        }

    }

    public function checkIfUserLikes($blog_id, $User_id)
    {

        if ($this->dataBase->query('SELECT * from likes where Blog_id =:Blog_id AND User_id =:User_id  ', [
                'Blog_id' => $blog_id
                , 'User_id' => $User_id
            ])->rowCount() !== 0)
            return 1;
        else
            return 0;

    }

    public function addComment($commentData, $user_id, &$message)
    {
        $comment_text = $commentData['comment_body'];
        try {
            if (!$this->validationClass->notEmpty($comment_text, $message)) {
                $message = 'the message is empty';
                return false;
            }

            if ($this->insertComment($commentData['commented_blog_id'], $user_id, $comment_text, $commentData['parent_comment_id'] ?? null, $message))
                return true;
            else
                return false;

        } catch (Exception $e) {
            $message = 'Error in adding comment try again later';
            return false;
        }

    }


    public function insertComment($blog_id, $user_id, $comment_text, $parent_comment_id, &$message = null)
    {
        try {
            if (empty($parent_comment_id)) {
                if ($this->dataBase->query('insert into comment_system(User_id,blog_id , comment_text) VALUES(:User_id , :blog_id ,:comment_text)', [
                    'User_id' => $user_id,
                    'blog_id' => $blog_id,
                    'comment_text' => $comment_text
                ])) {
                    $message = 'Comment inserted refresh the page to show the comment';
                    return true;
                } else {
                    $message = 'Error insert comment';
                    return false;

                }
            }
            if ($this->dataBase->query('insert into comment_system(User_id,blog_id , comment_text ,parent_comment_id) VALUES(:User_id , :blog_id ,:comment_text ,:parent_comment_id) ', [
                'User_id' => $user_id,
                'blog_id' => $blog_id,
                'comment_text' => $comment_text,
                'parent_comment_id' => $parent_comment_id
            ])) {
                $message = 'Comment inserted refresh the page to show the comment';
                return true;
            } else {
                $message = 'Error insert comment';
                return false;

            }
        } catch (Exception $e) {
            $message = 'Error while insert comment try again later';
            return false;
        }

    }

    public function getComments($blog_id)
    {
        try {
            $comments = $this->dataBase->query('
         SELECT comment_system.*, UsersInformation.userName 
         FROM comment_system INNER JOIN UsersInformation 
        ON comment_system.User_id = UsersInformation.id WHERE blog_id = :blog_id 
        ORDER BY created_at DESC ',
                [
                    'blog_id' => $blog_id,
                ])->fetchAll(PDO::FETCH_ASSOC);

            if (empty($comments)) {
                return false;
            } else
                return $comments;

        } catch (Exception $e) {
            return false;
        }
    }

}
