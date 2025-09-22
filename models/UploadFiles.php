<?php

namespace app\models;
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}
use app\core\DataBase;
use app\core\session;

class UploadFiles
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = new DataBase();
        $this->session = new Session();
    }

    public function checkIsItImg(array $file, &$uploadOk, &$msg): bool
    {
        $check = getimagesize($file['tmp_name']);
        if ($check !== false) {
            if ($check[2] == IMAGETYPE_PNG) {
                return $this->validSize($file, $uploadOk, $msg);
            } else {
                $uploadOk = 0;
                $msg = "The image must be .png";
                return false;
            }
        } else {
            $uploadOk = 0;
            $msg = "File is not an image.";
            return false;
        }
    }

    public function validSize(array $file, &$uploadOk, &$msg): bool
    {
        if ($file['size'] > 5000000) {
            $msg = "Sorry, your file is too large.";
            $uploadOk = 0;
            return false;
        }
        $uploadOk = 1;
        return true;
    }

//    public function deletePastImg($imgPath){
//        unlink($imgPath);
//        return true;
//    }

    public function addImg(array $file, &$uploadOk, &$msg = null)
    {
        if (!$this->checkIsItImg($file, $uploadOk, $msg)) {
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

            $msg = "The file " . basename($file['name']) . " has been uploaded.";

            return $this->db->updateUserData($this->session->get('id') ,'profileImg', $target_file);
        } else {
            $uploadOk = 0;
            $msg = "Error uploading the file.";
            return false;
        }
    }
}
