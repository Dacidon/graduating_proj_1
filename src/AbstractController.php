<?php

namespace Core;

use App\Model\User;

abstract class AbstractController {

    protected $view;
    protected $user;

    protected function redirect(string $url)
    {
        throw new RedirectException($url);
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}