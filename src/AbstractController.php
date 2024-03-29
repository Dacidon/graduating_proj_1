<?php
namespace Core;

use App\Model\User;

abstract class AbstractController {

    protected $view;
    protected $user;
    protected $session;

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

    public function getUser(): ?User
    {
        $userId = $this->session->getUserId();
        if (!$userId) {
            return null;
        }

        $user = User::getById($userId);
        if (!$user) {
            return null;
        }

        return $user;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
    }
}