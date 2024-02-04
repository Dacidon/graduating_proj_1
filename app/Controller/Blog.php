<?php

namespace App\Controller;

use Core\AbstractController;

class Blog extends AbstractController {
    function getMessages() {
        echo __METHOD__;
    }
}