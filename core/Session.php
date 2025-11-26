<?php

namespace app\core;


class Session
{
    private static $instance;

    private function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstance() : Session
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }



    public function get($key): mixed
    {
        return $_SESSION[$key] ?? null;

    }

    public function set($key, $value)
    {
        return $_SESSION[$key] = $value;


    }

    public function edit($key, $value): void
    {
        $_SESSION[$key] = $value;

    }

    public function has($key): bool
    {
        return isset($_SESSION[$key]);

    }

    public function delete($key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();

    }

    public function setSessionData($userData): void
    {
        $_SESSION['id'] = $userData['id'];
        $_SESSION['userName'] = $userData['userName'];
        $_SESSION['emailAddress'] = $userData['emailAddress'];
        $_SESSION['password'] = $userData['password'];
        $_SESSION['profileImg'] = '/public/default.png';
        $_SESSION['userRole'] = $userData['user_role'];

    }

    public function isGuest(): bool
    {
        return empty($this->get('id'));
    }

    public function isAdmin(): bool{
        if($_SESSION['userRole'] == 'admin')
            return true;
            else
               return false;

    }




}
