<?php

namespace Core;

abstract class AbstractController {
    protected $view;

    protected function redirect(string $url) {
        throw new RedirectException($url);
    }

    public function setView(View $view) {
        $this->view = $view;
    }
}