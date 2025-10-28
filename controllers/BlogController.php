<?php

namespace app\controllers;

use app\core\Controller;

class Blogs extends Controller{


    public function __construct(){



    }
    public function showBlogs():string
    {
        return $this->render('blog.view' ,['heading'=>'Blog']);
    }

}




// call the db function and function to show the blogs


