<?php

namespace App\Controller;

use Core\AbstractController;

class User extends AbstractController {
    function login() {
        echo __METHOD__;
    }

    function register() {
        return $this->view->render('/User/register.phtml');
    }
}