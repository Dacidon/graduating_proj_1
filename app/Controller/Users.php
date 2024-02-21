<?php
namespace App\Controller;

use App\Controller\Admin;
use App\Model\User;

class Users extends Admin
{
    public function index()
    {
        return $this->view->render(
            'admin/users.phtml',
            [
                'users' => User::getList()
            ]
        );
    }

    public function saveUser()
    {
        $id = (int) $_POST['id'];
        $name = (string) $_POST['name'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        $user = User::getById($id);
        if (!$user) {
            return $this->response(['error' => 'no user']);
        }

        if (!$name) {
            return $this->response(['error' => 'no name']);
        }

        if (!$email) {
            return $this->response(['error' => 'no email']);
        }

        if ($password && mb_strlen($password) < 5) {
            return $this->response(['error' => 'too short password']);
        }

        $user->name = $name;
        $user->email = $email;

        /** @var User $emailUser */
        $emailUser = User::getByEmail($email);
        if ($emailUser && $emailUser->id != $id) {
            return $this->response(['error' => 'this email already userd by uid#' . $emailUser->id]);
        }

        if ($password) {
            $user->password = User::getPasswordHash($password);
        }
        $user->save();

        return $this->response(['result' => 'ok']);

    }

    public function deleteUser()
    {
        $id = (int) $_POST['id'];

        $user = User::getById($id);
        if (!$user) {
            return $this->response(['error' => 'no user']);
        }

        $user->delete();

        return $this->response(['result' => 'ok']);
    }

    public function addUser()
    {
        $name = (string) $_POST['name'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        if (!$name || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$name) {
            return $this->response(['error' => 'no name']);
        }

        if (!$email) {
            return $this->response(['error' => 'no email']);
        }

        if (!$password || mb_strlen($password) < 5) {
            return $this->response(['error' => 'too short password']);
        }

        /** @var User $emailUser */
        $emailUser = User::getByEmail($email);
        if ($emailUser) {
            return $this->response(['error' => 'this email already used by uid#' . $emailUser->id]);
        }

        $userData = [
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'password' => User::getPasswordHash($password),
            'email' => $email,
        ];
        $user = new User($userData);
        $user->save();

        return $this->response(['result' => 'ok']);

    }

    public function response(array $data)
    {
        header('Content-type: application/json');
        return json_encode($data);
    }
}