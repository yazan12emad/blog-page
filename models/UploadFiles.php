<?php

namespace app\models;

use app\core\session;

class UploadFiles
{
    protected Session $session;

        Private array $acceptedFileTypes = ['.png', '.jpg', '.jpeg'];
        private int $acceptedFileSize = 5000000;

        private string $fileSavedPath = "public/";

    public function __construct()
    {
        $this->session = Session::getInstance();

    }
    public function addImg($file, &$message =  null): false|string
    {

        if (!$this->checkIsItImg($file,$message)) {
            return false;
        }

        $newFileName = uniqid("profile_", true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $target_file = $this->fileSavedPath . $newFileName;


        if (move_uploaded_file($file['tmp_name'], $target_file)) {

            $message = "The file " . basename($file['name']) . " has been uploaded.";

            return  $target_file;

        } else {
            $message = "Error uploading the file.";
            return false;
        }
    }


    public function checkIsItImg($file , &$message = null): bool
    {


        if (empty($file['tmp_name'])) {
            $message = "No file uploaded.";
            return false;
        }

        $check = @getimagesize($file['tmp_name']);
        if ($check !== false) {

            if (!in_array($check[2] ,$this->acceptedFileTypes)) {
                return $this->validSize($file, $message );
            }
                $message  = "The image must be .png";
                return false;
        }
            $message  = "File is not an image.";
            return false;

    }



    public function validSize(array $file, &$message =[]): bool
    {

        if ($file['size'] >= $this->acceptedFileSize) {
            $message  = "Sorry, your file is too large.";
            return false;
        }
        return true;
    }


}
