<?php

namespace app\controllers;
require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Controller;
use app\core\session;
use app\models\UserModel;

class AuthController extends Controller
{
    private Session $session;
    private UserModel $userModel;
    private array $messages = [];

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = Session::getInstance();
    }

    public function checkUserRole($userRole): bool
    {
        return $this->requireRole($userRole);
    }


    public function login(){
        if(!$this->checkUserRole('guest')) {
            $this->redirect('home');
        }

        if (!$this->isPost()) {
            return $this->render('logIn.view', [
                'heading' => 'log In',
            ]);
        }

        $userInputData = $this->post();

        $authenticatedUser = $this->userModel->logInUser($userInputData ,  $this->messages['logInPageError']);

        if(!$authenticatedUser) {
            return $this->render('logIn.view', [
                'heading' => 'log In',
                'success' => false,
                'message' => $this->messages,
            ]);
        }
        $this->session->setSessionData($authenticatedUser);
        $this->redirect('home');
    }

    public function register()
    {
        if (!$this->checkUserRole('guest')) {
            $this->redirect('home');
        }

        if (!$this->isPost()) {
            return $this->render('signUp.view', [
                'heading' => 'log In',
            ]);
        }

        $userInputData = $this->post();

        $registeredUser = $this->userModel->addUser($userInputData, $this->messages['signUpPageError']);

        if (!$registeredUser){
            return $this->render('signUp.view', [
                'heading' => 'sign Up',
                'success' => false,
                'message' => $this->messages
            ]);
        }

        $this->session->setSessionData($registeredUser);
        $this->redirect('home');
    }



    public function profile()
    {
        if ($this->checkUserRole('guest')){
            $this->redirect('home');
        }

        if (!$this->isPost()) {
            return $this->profileRender();
        }

        try {
            $userCurrentData = $this->userModel->getUserDataById($this->session->get('id'));
        }
        catch (\DomainException $e){
            $this->session->destroy();
            $this->redirect('home');
        }

        $userInputData = [
            'userName' => $this->post('userName') ?? null ,
            'emailAddress' => $this->post('emailAddress') ?? null,
            'Image' => $_FILES['profileImage'] ?? null,
            'currentPassword' => $this->post('currentPassword') ?? null,
            'newPassword'=> $this->post('newPassword') ?? null,
        ];

         if($this->userModel->saveProfileChanges($userCurrentData ,$userInputData ,$this->messages , $profileChanges)) {
             foreach ($profileChanges as $key => $value) {
                 $this->session->edit($key, $value);
             }
         }

        return $this->profileRender(['profileMessage' => $this->messages]);
    }

    private function profileRender(array $extra = []): string
    {
        $userFullData = $this->session->getAllSessionData();

        return $this->render('profile.view', array_merge([
            'heading' => 'profile',
            'userImage' => $userFullData['profileImg'],
            'currentUserName' => $userFullData['userName'],
            'currentUserEmail' => $userFullData['emailAddress'],

        ], $extra));
    }



    public function forgetPassword(): string
    {
        if (!$this->isPost()) {
            return $this->render('forgetPassword.view', ['heading' => 'Reset password']);
        }

            $emailAddress = $this->post('emailAddress');
            $resetUserPassword = $this->userModel->forgetPassword($emailAddress);


                if($resetUserPassword['success']) {
                    $this->session->set('resetID', $resetUserPassword['id']);
                }

                return $this->render('forgetPassword.view',
                    ['heading' => 'forgetPassword',
                        'actionSuccess' => $resetUserPassword['success'],
                        'statusMessage' => $resetUserPassword['statusMessage']
                    ]);
        }

    public function submitNewPassword(): string
    {
        if (!$this->isPost()) {
            return $this->render('submitNewPassword.view', ['heading' => 'Reset password']);
        }

        $userId = $this->session->get('resetID');
        $userDataInput = $this->post();

        $submitUserPassword = $this->userModel->submitNewPassword($userDataInput, $userId);

        if($submitUserPassword['success']) {
            $this->session->destroy();
        }

            return $this->render('submitNewPassword.view', [
                'heading' => 'Reset password',
                    'actionSuccess' => $submitUserPassword['success'],
                    'statusMessage' => $submitUserPassword['statusMessage']
                ]
            );
        }

    public function logout(): void
    {
        $this->session->destroy();
        $this->redirect('home');
    }

}