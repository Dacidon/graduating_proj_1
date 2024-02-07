<?php

namespace App\Controller;

use Core\AbstractController;

class Blog extends AbstractController 
{

    function getMessages() 
    {
        if (!$this->user) {
            $this->redirect('/user/login');
        }

        return $this->view->render('Blog/index.phtml', [
            'user' => $this->user
        ]);
    }
}