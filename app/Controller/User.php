<?php

namespace App\Controller;

use App\Model\User as UserModel;
use Core\AbstractController;

class User extends AbstractController {

    public function login()
    {
        $email = trim($_POST['email']);

        if ($email) {
            $password = $_POST['password'];
            $user = UserModel::getByEmail($email);
            if (!$user) {
                $this->view->assign('error', 'Неверный Email или пароль');
            }

            if ($user) {
                if ($user->getPassword() != UserModel::getPasswordHash($password)) {
                    $this->view->assign('error', 'Неверный Email или пароль');
                } else if ($user->getEmail() != $email) {
                    $this->view->assign('error', 'Неверный Email или пароль');
                } else {
                    $user->setLastLoginDate();
                    $_SESSION['user_id'] = $user->getId();
                    $this->redirect('/blog');
                }
            }
        }

        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int) $_GET['user_id'])
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

                $_SESSION['user_id'] = $user->getId();
                $this->setUser($user);

                $this->redirect('/blog/index');
            }
        }

        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int) $_GET['user_id'])
        ]);
    }

    public function profileAction()
    {
        return $this->view->render('User/profile.phtml', [
            'user' => UserModel::getById((int) $_GET['user_id'])
        ]);

    }

    public function logoutAction()
    {
        session_destroy();

        $this->redirect('/user/login');

    }
}