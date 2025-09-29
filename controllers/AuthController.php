<?php

namespace app\controllers;
require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Controller;
use app\core\session;
use app\models\ValidationClass;
use app\models\UserModel;
use app\models\UploadFiles;
use app\models\ResetPasswordModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    private $session;
    private $VC;
    private $UM;
    private $UF;
    private $RP;
    private $navData;

    public function __construct()
    {

//        $this->session = new Session();
        $this->VC = new ValidationClass();
        $this->UM = new UserModel();
        $this->UF = new UploadFiles();
        $this->RP = new ResetPasswordModel();
        $this->session = Session::getInstance();


    }

    public function showHome(): string
    {
        var_dump($_SESSION);
        if ($this->session->has('id'))
            return $this->render('home.view', ['heading' => 'home', 'navData' => true]);
        else
            return $this->render('home.view', ['heading' => 'home']);
    }


    public function login(): string
    {
        if (isset($_SESSION['id'])) {
            header('Location: ../');
            die('Direct access is not permitted.');
        }

        if ($this->isPost()) {


            $userName = trim($_POST['userName']);
            $password = trim($_POST['password']);

            if (!$this->VC->notEmpty($userName)) {
                return $this->render('logIn.view', ['userError' => 'Username or email is required', 'heading' => ' log In ']);
            } else if (!$this->VC->validationPassword($password)) {
                return $this->render('logIn.view', ['passError' => 'Password must be at least 8 characters long', 'heading' => ' log In ']);
            } else {
                // Determine input type
                if (filter_var($userName, FILTER_VALIDATE_EMAIL)) {
                    $user = $this->UM->getUserEmail($userName);
                } else {
                    $user = $this->UM->getUserInfo($userName);
                }

                // Check password
                if ($user && password_verify($password, $user['password'])) {
                    // get the data for session fron the user var (take values form DB )

                    $this->session->setSessionData($user);
                    return $this->showHome(true);


                } else if ($user['password'] === $password) {
                    // get the data for session fron the user var (take values form DB )
                    $this->session->setSessionData($user);
                    $this->navData = true;
                    return $this->showHome();

                } else {
                    return $this->render('logIn.view', ['logInError' => 'Invaled Username or Password', 'heading' => ' log In ']);
                }
            }
        }
        return $this->render('logIn.view', [
            'heading' => 'log In',
        ]);

    }

    public function register()
    {
        if (isset($_SESSION['id'])) {
            header('Location: ../');
            die('Direct access is not permitted.');
        }

        if ($this->isPost()) {

            $userName = trim($_POST['userName']);
            $emailAddress = trim($_POST['emailAddress']);
            $password = trim($_POST['password']);
            $chickUserName = $this->UM->getUserInfo($userName);
            $chickUserEmail = $this->UM->getUserEmail($emailAddress);

            if (!($this->VC->notEmpty($userName))) {
                return $this->render('signUp.view', ['userError' => 'Username is required', 'heading' => 'sign Up',]);
            } else if ($chickUserName && $chickUserName['userName'] === $userName) {
                return $this->render('signUp.view', ['userError' => 'Username is already taken', 'heading' => 'sign Up',]);
            }

            if (!($this->VC->validateEmail($emailAddress))) {
                return $this->render('signUp.view', ['emailAddressError' => 'Email Address is required', 'heading' => 'sign Up',]);

            }

            if ($chickUserEmail && $chickUserEmail['emailAddress'] === $emailAddress) {
                return $this->render('signUp.view', ['emailAddressError' => 'Email is already taken', 'heading' => 'sign Up',]);
            }

            if (!($this->VC->validationPassword($password))) {
                return $this->render('signUp.view', ['passwordError' => 'Password must be at least 8 characters long', 'heading' => 'sign Up',]);
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $this->UM->addNewUser($userName, $emailAddress, $hashedPassword);
                $this->session->setSessionData($this->UM->getUserInfo($userName));

                return $this->render('home.view', ['navData' => 'true']);
            }
        }
        return $this->render('signUp.view', [
            'heading' => 'sign Up',

        ]);

    }

    public function profile(): string
    {

        if (!isset($_SESSION['id'])) {
            header('Location: ../');
            die('Direct access is not permitted.');
        }

        $currentID = trim($this->session->get("id"));
        $currentUserName = trim($this->session->get("userName"));
        $currentUserEmail = trim($this->session->get("emailAddress"));
        $oldPassword = $this->UM->getUserInfo($currentUserName)['password'];
        $imgBefore = $this->UM->getUserImg($currentID)['profileImg'];
        $this->session->set("imgBefore", $imgBefore);
        $msg = '';

        if ($this->isPost()) {
            // change the profile img
            if (isset($_FILES['profileImage']) && !empty($_FILES['profileImage']['tmp_name'])) {
                $uploadOk = 0;
                $newImg = $this->UF->addImg($_FILES['profileImage'], $uploadOk, $msg);

                if ($newImg !== false) {
//            $UF->deletePastImg(($imgBefore));
                    $imgBefore = $this->UM->getUserImg($currentID)['profileImg'];
                    return $this->profileRender(['imgError' => $msg]);


                } else {
                    $imgAfter = $imgBefore;
                    return $this->profileRender(['imgError' => $msg]);

                }
            }

            // user name validation ((((((done))))))
            if (!empty($_POST['userName']) && $_POST['userName'] !== $currentUserName) {
                if (!$this->VC->validateUserNameEdit($currentUserName, $_POST['userName'], $msg)) {
                    return $this->profileRender(['userNameError' => $msg]);

                } else if ($this->UM->checkUserData('userName', $_POST['userName'])) {
                    $msg = 'Username is already taken';
                    return $this->profileRender(['userNameError' => $msg]);

                } else if ($this->UM->updateUserData($currentID, 'userName', $_POST["userName"])) {
                    $currentUserName = $this->session->set("userName", $_POST["userName"]);
                    $msg = 'User name changed successfully';
                    return $this->profileRender(['userNameError' => $msg]);

                }
            }

            // email validation (((((done))))))
            if (!($currentUserEmail === $_POST["emailAddress"])) {
                if (!$this->VC->validateEmailEdit($currentUserEmail, $_POST['emailAddress'], $msg)) {
                    return $this->profileRender(['emailAddressError' => $msg]);

                } else if ($this->UM->checkUserData('emailAddress', $_POST['emailAddress'])) {
                    $msg = 'Email is already taken';
                    return $this->profileRender(['emailAddressError' => $msg]);

                } else if ($this->UM->updateUserData($currentID, 'emailAddress', $_POST["emailAddress"])) {
                    $currentUserEmail = $this->session->set("emailAddress", $_POST["emailAddress"]);
                    $msg = 'Email changed successfully';
                    return $this->profileRender(['emailAddressError' => $msg]);


                }
            }
            // password validation  (((((done))))))
            if (!$this->VC->validateNewPasswordEdit($oldPassword, $_POST['currentPassword'], $_POST['newPassword'], $msg)) {
                return $this->profileRender(['passwordError' => $msg]);

            } else {
                if ($this->UM->updateUserData($currentID, 'password', password_hash($_POST['newPassword'], PASSWORD_DEFAULT))) {
                    $this->session->set('password', $_POST['newPassword']);
                    $oldPassword = $_POST['newPassword'];
                    $msg = 'Password updated successfully';
                    return $this->profileRender(['passwordError' => $msg]);

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
        $imgBefore = $this->UM->getUserImg($currentID)['profileImg'];

        return $this->render('profile.view', array_merge([
            'heading' => 'profile',
            'imgBefore' => $imgBefore,
            'currentUserName' => $currentUserName,
            'currentUserEmail' => $currentUserEmail,
            'navData' => true,
        ], $extra));
    }

    public function logout(): string
    {

        $this->session->destroy();
        return $this->showHome();

//                if ($this->session->has('id')) {
//                    return [
//                        'logged_in' => true,
//                        'userName'  => $this->session->get('userName') ?? 'Guest'
//                    ];
//                }
//
//                return [
//                    'logged_in' => false
//                ];
    }

    public function forgetPassword()
    {

        if ($this->isPost()) {

            $emailAddress = $_POST['emailAddress'];

            if (!$this->VC->validateEmail($emailAddress)) {
                return $this->render('forgetPassword.view',
                    ['heading' => 'Reset password',
                        'forgetPasswordError' => 'Please enter a valid email address']);

            } else if (!$userInfo = $this->UM->getUserEmail($emailAddress)) {
                return $this->render('forgetPassword.view',
                    ['heading' => 'Reset password',
                        'forgetPasswordError' => 'email address does not exist']);
            } else {
                try {

                    $this->session->setSessionData($userInfo);


                    $token = bin2hex(random_bytes(4));
                    $expireDate = date("Y-m-d H:i:s", strtotime("+30 minutes"));
                    $this->RP->saveResetToken($userInfo['id'], $token, $expireDate);
                    $resetLink = 'http://localhost:8000/submitNewPassword.php';

                    try {
                        $mail = new PHPMailer(true);

                        // set SMTP
                        $mail->isSMTP();
                        $mail->Host = 'sandbox.smtp.mailtrap.io';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'bba99b64486755';
                        $mail->Password = 'c7e0becec9c2d9';
                        $mail->Port = 2525;

//            $mail->Host = $SMTPKeys['Host'];
//            $mail->SMTPAuth = true;
//            $mail->Username = $SMTPKeys['Username'];
//            $mail->Password = $SMTPKeys['Password'];
//            $mail->Port = $SMTPKeys['Port'];

                        $mail->setFrom('no-reply@yourapp.com', 'Your App');
                        $mail->addAddress($emailAddress);
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $mail->Body = "
        <p>Hi,</p>
        <p>You requested to reset your password. Click the link below:</p>
        <p><a href='$resetLink'>$resetLink</a></p>
                <p> your reset code is '$token' </p>
        <p>This link will expire in 30 minutes.</p>
    ";
                        $mail->send();

                        $this->session->set('success', 'Reset link sent to your email!');
                        echo $this->session->get('success');

                    } catch (Exception $e) {
                        return $this->render('forgetPassword.view', [
                            'forgetPasswordError' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"
                        ]);
                    }

                } catch (\Random\RandomException $e) {

                }
                exit;
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
            $savedToken = $this->RP->getToken($currentID);

            if (!$savedToken) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'tokenError' => 'Token not found or already used'
                ]);
            }

            else if ($savedToken['token'] !== $inputToken) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'tokenError' => 'Invalid token'
                ]);
            }

            else if (!$this->VC->validationPassword($newPassword)) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'newPasswordError' => 'Invalid new password'
                ]);
            }

            else if (!$this->VC->validationPassword($confirmPassword)) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'confirmPasswordError' => 'Confirm password is invalid'
                ]);
            }

            else if ($newPassword !== $confirmPassword) {
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'confirmPasswordError' => 'Passwords do not match'
                ]);
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->UM->updateUserData($currentID, 'password', $hashedPassword)) {
                $this->RP->markTokenAsUsed($currentID, $savedToken['token']);
                return $this->render('submitNewPassword.view', [
                    'heading' => 'Reset password',
                    'passChange' => 'true'
                ]);
            }

            return $this->render('submitNewPassword.view', [
                'heading' => 'Reset password',
                'error' => 'Error updating password'
            ]);
        }

        return $this->render('submitNewPassword.view', ['heading' => 'Reset password']);
    }


}