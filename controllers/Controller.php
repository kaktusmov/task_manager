<?php

namespace app\controllers;

class Controller
{
    private $home = '/';

    protected function render($fileName, $data = []) {

        extract($data);

        if (isset($_SESSION['success']))
        {
            $success = include('../views/success.php');
            unset($_SESSION['success']);
        }
        else
            $success = '';

        if (isset($_SESSION['error']))
        {
            $error = include('../views/error.php');
            unset($_SESSION['error']);
        }
        else
            $error = '';

        $auth = [
            'link' => ($this->isAuthorized() ? 'logout':'login'),
            'value' => ($this->isAuthorized() ? 'Выйти' : 'Войти')
        ];

        include ("../views/layout.php");
    }

    protected function goHome()
    {
        header('Location: '.$this->home);
        return true;
    }

    protected function redirect($path)
    {
        header('Location: '.$this->home.$path);
        return true;
    }

    protected function isAuthorized()
    {
        return isset($_SESSION['authorized']);
    }
}