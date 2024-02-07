<?php
namespace App\Model;

use Core\AbstractModel;

class User extends AbstractModel 
{
    private $user_id;
    private $username;
    private $password;
    private $email;
    private $last_login;

    public function __construct($data = [])
    {
        if ($data) {
            $this->user_id = $data['user_id'];
            $this->username = $data['username'];
            $this->password = $data['password'];
            $this->email = $data['email'];
        }
    }

    
    public function getName(): string
    {
        return $this->username;
    }

    public function setName(string $username)
    {
        $this->username = $username;
        return $this;
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function setLastLoginDate()
    {
        $this->last_login = date('d M Y H+3:i:s:v, D');

        $db = \Core\Db::getInstance();
        $update = "UPDATE users SET last_login=:last_login WHERE user_id=:user_id";
        $db->exec($update, __METHOD__, [
            ':last_login' => $this->last_login,
            ':user_id' => $this->user_id
        ]);
    }

    public function save()
    {
        $db = \Core\Db::getInstance();
        $insert = "INSERT INTO users (`username`, `password`, `email`) VALUES (:username, :password, :email)";
        $db->exec($insert, __METHOD__, [
            ':username' => $this->username,
            ':password' => $this->password,
            ':email' => $this->email
        ]);

        $user_id = $db->lastInsertId();
        $this->user_id = $user_id;
        
        return $user_id;
    }

    public static function getById(int $user_id): ?self
    {
        $db = \Core\Db::getInstance();
        $select = "SELECT * FROM users WHERE user_id = $user_id";
        $data = $db->fetchOne($select, __METHOD__);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    public static function getByEmail(string $email): ?self
    {
        $db = \Core\Db::getInstance();
        $select = "SELECT * FROM users WHERE `email` = :email";
        $data = $db->fetchOne($select, __METHOD__, [
            ':email' => $email
        ]);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    public static function getByName(string $username): ?self
    {
        $db = \Core\Db::getInstance();
        $select = "SELECT * FROM users WHERE `username` = :username";
        $data = $db->fetchOne($select, __METHOD__, [
            ':username' => $username
        ]);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    public static function getPasswordHash(string $password)
    {
        return sha1('d!a.c,i&d?o*n(' . $password);
    }

    public static function isUserAuthorized(): bool
    {
        return isset($_SESSION['user_id']);
    }
}