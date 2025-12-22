<?php
// This file for check if the user do :
// post any form
// redirect if the user search for files un access files
//by ob_start print the return form the controllers print it with view


namespace app\core;
    abstract class Controller
{
    private Session $session;
    public string $layout = '';

    public function getSession(): Session
    {
        return $this->session = Session::getInstance();
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function post($key = null){
        if (empty($key)) return $_POST;
        return $_POST[$key] ?? null;
    }

    public function requireRole($authStatus): bool
    {
        return  $this->getSession()->userRole() == $authStatus;
    }

    public function redirect($url) : void
    {
        header('Location: /'.$url );
        exit;
    }

    public function navData():array{
        if ($this->getSession()->userRole() === 'guest'){
            return [
                'logIn' => false,
                'role' =>'guest',
            ];
        }

        if ($this->getSession()->userRole() === 'admin') {
            return [
                'logIn' => true ,
                'role' =>'admin',
                'admin_id' => $this->session->get('id'),
            ];
        }

        return [
            'logIn' => true ,
            'role' =>'user' ,
            'user_id' => $this->session->get('id'),
        ];
    }

    public function render(string $string, array $array = []): string
    {
        // add layout here
        ob_start();
        $array = array_merge($array, ['navData' => $this->navData()]);
        extract($array);
        require_once ROOT_PATH . '/views/' . $string . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function jsonResponse(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }


//    public function partialRender(string $string, array $array = []):string
//    {
//        ob_start();
//        extract($array);
//        require_once ROOT_PATH . '/views/' . $string . '.php';
//        $content = ob_get_contents();
//        ob_end_clean();
//        return $content;
//    }
}