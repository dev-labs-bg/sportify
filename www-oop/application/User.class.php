<?php

namespace Devlabs\App;

class User
{
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $passwordConfirm;
    public $passwordHash;

    public function add()
    {
        $result = $GLOBALS['db']->query(
            "INSERT IGNORE INTO users(first_name,last_name,email,password_hash)
                VALUES(:first_name, :last_name, :email, :password_hash)",
            array('first_name' => $this->firstName, 'last_name' => $this->lastName, 'email' => $this->email,
                'password_hash' => password_hash($this->password, PASSWORD_DEFAULT))
        );
    }

    public function change()
    {

    }

    public function remove()
    {

    }
}
