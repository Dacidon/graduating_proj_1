<?php

namespace App\Controller;

use App\Model\User as UserModel;
use Core\AbstractController;

class User extends AbstractController {

    public function index()
    {
        if ($this->getUser()) {
            $this->redirect('/blog');
        }

        return $this->view->render(
            'login.phtml',
            [
                'title' => 'Главная страница',
                'user' => $this->getUser(),
            ]
        );
    }

    public function login()
    {

        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $user = UserModel::getByEmail($email);
            if (!$user) {
                $this->view->assign('error', 'Неверный Email или пароль');
            }

            if ($user) {
                if ($user->getEmail() != $email) {
                    $this->view->assign('error', 'Неверный Email или пароль');
                } else if ($user->getPassword() != UserModel::getPasswordHash($password)) {
                    $this->view->assign('error', 'Неверный Email или пароль');
                } else {
                    $user->setLastLoginDate();
                    $_SESSION['user_id'] = $user->getId();
                    header('Location: /blog/index');
                }
            }
        }

        return $this->view->render('User/login.phtml', [
            'user' => UserModel::getById((int) $_SESSION['user_id'])
        ]);
    }

    public function register()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $password_check = trim($_POST['password_check']);
        $email = trim($_POST['email']);

        $success = true;
        if (isset($_POST['username'])) {

            if (!$username) {
                $this->view->assign('error', 'Имя не может быть пустым');
                $success = false;
            }

            if (!$password) {
                $this->view->assign('error', 'Пароль не может быть пустым');
                $success = false;
            }

            if (strlen($password) < 4) {
                $this->view->assign('error', 'Пароль должен быть длиннее 4 символов');
                $success = false;
            }

            if (!$password_check) {
                $this->view->assign('error', 'Подтвердите пароль');
                $success = false;
            }

            if ($password !== $password_check) {
                $this->view->assign('error', 'Пароли не совпадают');
                $success = false;
            }

            if (!$email) {
                $this->view->assign('error', 'Email не может быть пустым');
                $success = false;
            }

            $user = UserModel::getByName($username);
            if ($user) {
                $this->view->assign('error', 'Пользователь с таким именем уже существует');
                $success = false;
            }

            if ($success) {
                $user = (new UserModel())
                    ->setName($username)
                    ->setPassword(UserModel::getPasswordHash($password))
                    ->setEmail($email);

                $user->save();

                $this->session->authUser($user->getId());
                $this->setUser($user);
                return $this->view->render('Blog/index.phtml', [
                    'user' => UserModel::getById((int) $_SESSION['user_id'])
                ]);
            }
        }

        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int) $_SESSION['user_id'])
        ]);
    }

    public function profile()
    {
        return $this->view->render('User/profile.phtml', [
            'user' => UserModel::getById((int) $_SESSION['user_id'])
        ]);

    }

    public function logout()
    {
        session_destroy();

        $this->redirect('/user/login');

    }
}