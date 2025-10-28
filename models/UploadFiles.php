<?php

namespace app\models;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

use app\core\session;

class UploadFiles
{
//    protected ;
    protected $session;


    public function __construct()
    {
       // $this->db = new DataBase();
        $this->session = Session::getInstance();

    }
    public function addImg( $file = [],  &$msg = []): false|string
    {
        if (!$this->checkIsItImg($file,$msg)) {
            return false;
        }

        $target_dir = "public/";
        $newFileName = uniqid("profile_", true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $target_file = $target_dir . $newFileName;

        //Is this necessary ?
        //
//        if (!is_dir($target_dir)) {
//            mkdir($target_dir, 0755, true);
//        }

        if (move_uploaded_file($file['tmp_name'], $target_file)) {

            $msg['errorUploadImg'] = "The file " . basename($file['name']) . " has been uploaded.";

            return  $target_file;

        } else {
            $msg['errorUploadImg'] = "Error uploading the file.";
            return false;
        }
    }


    public function checkIsItImg( $file = [], &$msg = []): bool
    {

        if (empty($file['tmp_name'])) {
            $msg['errorUploadImg'] = "No file uploaded.";
            return false;
        }

        $check = @getimagesize($file['tmp_name']);

        if ($check !== false) {
            if ($check[2] == IMAGETYPE_PNG) {
                return $this->validSize($file, $msg);
            } else {
                $msg['errorUploadImg'] = "The image must be .png";
                return false;
            }
        } else {
            $msg['errorUploadImg'] = "File is not an image.";
            return false;
        }
    }



    public function validSize(array $file, &$msg = []): bool
    {
        if ($file['size'] >= 5000000) {
            $msg['errorUploadImg'] = "Sorry, your file is too large.";
            return false;
        }
        return true;
    }

//    public function deletePastImg($imgPath){
//        unlink($imgPath);
//        return true;
//    }

}
