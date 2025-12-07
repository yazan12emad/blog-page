<?php

namespace app\models;
use app\core\DataBase;

class FullViewModel
{
    public DataBase $DB ;

    public function __construct()
    {
        $this->DB =DataBase::getInstance();

    }

    public function checkIfUserLikes($blog_id , $User_id){
        if($this->DB->query('SELECT * from likes where Blog_id =:Blog_id AND User_id =:User_id  ',[
            'Blog_id' => $blog_id
                ,'User_id' => $User_id
        ])->rowCount() !== 0)
            return 'true';
        else
            return 'false';

    }
    public function LikeAction($action , $blog_id ,$user_id ,  & $msg = null){
        if($action == 'addLike')
            if($this->DB->query('update blog set Likes = Likes+1 WHERE blog_id =:blog_id',[
                'blog_id' => $blog_id
        ]
    ) && $this->DB->query('INSERT INTO likes (Blog_id,User_id ,Action) VALUES(:blog_id, :User_id ,:Action )',[
        'blog_id' => $blog_id,
                    'User_id' => $user_id ,
                    'Action' => $action

                ]))
                return true;
        else {
            $msg = 'Error adding like ';
            return false;

        }

        if($action == 'removeLike')
            if($this->DB->query('update blog set Likes = Likes-1 WHERE blog_id =:blog_id',[
                    'blog_id' => $blog_id
                ]
            )
      &&  $this->DB->query('Delete from Likes where blog_id =:blog_id AND User_id =:User_id  ',[
          'blog_id' => $blog_id
                    ,'User_id' => $user_id
                ]))
                return true;

        else{
            $msg = 'Error removing like ';
            return false;
        }

            else
                return false;



    }
}