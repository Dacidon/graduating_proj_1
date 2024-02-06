<?php

namespace App\Controller;

use App\Model\User as UserModel;
use Core\AbstractController;

class Blog extends AbstractController 
{

    function getMessages() 
    {

        if (!$this->user) {
            $this->redirect('user/register');
        }

        return $this->view->render('Blog/index.phtml', [
            'user' => $this->user
        ]);
    }
}