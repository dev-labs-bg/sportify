<?php

namespace Devlabs\App;

/**
 * Class User
 * @package Devlabs\App
 */
class User
{
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $passwordConfirm;
    public $passwordHash;

    /**
     * Method for adding a new user into the database
     *
     * @return mixed
     */
    public function add()
    {
        return $GLOBALS['db']->query(
            "INSERT IGNORE INTO users(first_name,last_name,email,password_hash)
                VALUES(:first_name, :last_name, :email, :password_hash)",
            array('first_name' => $this->firstName, 'last_name' => $this->lastName, 'email' => $this->email,
                'password_hash' => password_hash($this->password, PASSWORD_DEFAULT))
        );
    }

    /**
     * Method for removing a user from the database
     *
     * @return mixed
     */
    public function remove()
    {
        return $GLOBALS['db']->query(
            "DELETE FROM users WHERE email = :email",
            array('email' => $this->email)
        );
    }

    /**
     * Change a user's password in the database
     *
     * @param $password
     * @return mixed
     */
    public function changePassword($password)
    {
        $this->password = $password;

        return $GLOBALS['db']->query(
            "UPDATE users SET password_hash = :password_hash WHERE email = :email",
            array('email' => $email, 'password_hash' => password_hash($password, PASSWORD_DEFAULT))
        );
    }

    /**
     * Set a user as confirmed in the database
     *
     * @return mixed
     */
    public function setConfirmed()
    {
        return $GLOBALS['db']->query(
            "UPDATE users SET confirmed = 1 WHERE email = :email",
            array('email' => $this->email)
        );
    }

    /**
     * Check if user present in the database
     *
     * @return mixed
     */
    public function lookup()
    {
        return $GLOBALS['db']->query(
            "SELECT * FROM users WHERE email = :email",
            array('email' => $this->email)
        );
    }
}
