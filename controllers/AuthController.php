<?php

namespace app\controllers;
require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Controller;
use app\core\session;
use app\models\UserModel;
use app\controllers\User;
use app\models\ResetPasswordModel;


class AuthController extends Controller
{
    private $session;
    private $userModel;
    private $ResetPasswordModel;

//     private $User;
    private $msg = [];

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ResetPasswordModel = new ResetPasswordModel();
        $this->session = Session::getInstance();
//        $this->User = User::getInstance();

    }



    public function login(): string
    {

        if (!$this->session->isGuest()) {
            $this->redirect('.');
        }
        if ($this->isPost()) {

            $userData = $this->userModel->logInUser(
                trim($_POST['userName']),
                trim($_POST['password']),
                $this->msg);

            if(!empty($this->msg))
            {
            return $this->render('logIn.view', ['heading' => 'log In', 'error' => $this->msg ]);

            }
            else {

                $this->session->setSessionData($userData);
                $this->redirect('home');
            }
        }

        return $this->render('logIn.view', ['heading' => 'log In',]);

}

    public function register()
    {
        if (!$this->session->isGuest()) {
            $this->redirect('.');

        }

        if ($this->isPost()) {
            $newUser = $this->userModel->addUser(
                $_POST['userName'],
                $_POST['emailAddress'],
                $_POST['password'],
                $this->msg);

             if (!empty($this->msg))
                return $this->render('signUp.view', ['heading' => 'sign Up', 'error' => $this->msg ]);
            else {
                $this->session->setSessionData($newUser);
                     $this->redirect('home');
            }
        }
        return $this->render('signUp.view', [
            'heading' => 'sign Up'

        ]);

    }

    public function profile(): string
    {

        if ($this->session->isGuest()) {
            $this->redirect('.');
        }

        $currentID = $this->session->get("id");
        $currentUserName = trim($this->session->get("userName"));
        $currentUserEmail = trim($this->session->get("emailAddress"));
        $oldPassword = $this->userModel->getUserInfo($currentUserName)['password'];
        $imgBefore = $this->userModel->getUserImg($currentID)['profileImg'];
        $this->session->set("imgBefore", $imgBefore);

        if ($this->isPost()) {
            //   change img //
            if (isset($_FILES['profileImage']) && !empty($_FILES['profileImage']['tmp_name'])) {
                $result = $this->userModel->changeProfile($currentID,
                    $_FILES['profileImage']);

                if (!$result)
                    return $this->profileRender(['error' => $this->msg ,'imgAfter'=> $imgBefore]);

                else
                    return $this->profileRender(['error' => $this->msg, 'imgAfter' => $result]);

            } //   change userName //
            else if (!empty($_POST['userName']) && $_POST['userName'] !== $currentUserName) {
                $result = $this->userModel->changeProfileUserName($currentID,
                    $_POST['userName'],
                    $currentUserName,
                    $this->msg);

                if (!$result)
                    return $this->profileRender(['error' => $this->msg]);

                else {
                    $this->session->set('userName', $result);
                    return $this->profileRender(['error' => $this->msg]);
                }

                    // change email //
            } else if (!($currentUserEmail === $_POST["emailAddress"])) {
                $result = $this->userModel->changeProfileEmail($currentID,
                    $_POST['emailAddress'],
                    $currentUserEmail,
                    $this->msg);
                if (!$result) {
                    return $this->profileRender(['error' => $this->msg]);
                } else {
                    $this->session->set('emailAddress', $result);
                    return $this->profileRender(['error' => $this->msg]);
                }
            }

            // password validation  (((((done))))))
            if (!empty($_POST['currentPassword'])&& !empty($_POST['newPassword'])&& $_POST['newPassword'] !== $oldPassword) {
                $result = $this->userModel->changeProfilePassword($currentID,
                    $oldPassword,
                    $_POST['currentPassword'],
                    $_POST['newPassword'],
                    $this->msg);
                if(!$result)
                return $this->profileRender(['error' => $this->msg]);
                else {
                    
                return $this->profileRender(['error' => $this->msg]);


                }

            }



        }
                    return $this->profileRender();

    }
    
    private function profileRender(array $extra = []): string
    {
        $currentID = trim($this->session->get("id"));
        $currentUserName = trim($this->session->get("userName"));
        $currentUserEmail = trim($this->session->get("emailAddress"));
        $imgBefore = $this->userModel->getUserImg($currentID)['profileImg'];

        return $this->render('profile.view', array_merge([
            'heading' => 'profile',
            'imgAfter' => $imgBefore,
            'currentUserName' => $currentUserName,
            'currentUserEmail' => $currentUserEmail,


        ], $extra));
    }



    public function forgetPassword(): string
    {
        if ($this->isPost()) {
            $emailAddress = $_POST['emailAddress'];

            $userInfo=$this->userModel->forgetPassword($emailAddress ,$this->msg );
            if(!$userInfo) {
                return $this->render('forgetPassword.view',
                    ['heading' => 'forgetPassword', 'error' => $this->msg]);
            } else {
                $this->session->set('id', $userInfo['id']);
                return $this->render('forgetPassword.view', [
                    'heading' => 'Forget Password',
                    'error' => $this->msg
                ]);
            }
        }

        return $this->render('forgetPassword.view', ['heading' => 'Reset password']);

    }


    public function submitNewPassword(): string
    {
        if ($this->isPost()) {
            $currentID = $this->session->get('id');
            $inputToken = trim($_POST['token']);
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);
            $savedToken = $this->ResetPasswordModel->getToken($currentID);

            if (!$this->userModel->submitNewPassword(
                $currentID,
                $inputToken,
                $newPassword,
                $confirmPassword,
                $savedToken,
                $this->msg)) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'error' => $this->msg
                ]);
            } else {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'error' => $this->msg]);

            }
        }



        return $this->render('submitNewPassword.view', ['heading' => 'Reset password']);
    }


    public function logout()
    {

        $this->session->destroy();
        $this->redirect('.');
    }

}