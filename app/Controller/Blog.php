<?php

namespace App\Controller;

use Core\AbstractController;

class Blog extends AbstractController {
    function getMessages() {
        if (isset($_GET['redirect'])) {
            $this->redirect('user/register');
        }
        echo __METHOD__;
    }
}