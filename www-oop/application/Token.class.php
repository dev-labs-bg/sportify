<?php

namespace Devlabs\App;

/**
 * Class Token
 * @package Devlabs\App
 */
class Token
{
    public $id;
    public $userId;
    public $purpose;
    public $value;
    public $datetime;

    /**
     * Load token data from database by passing a token value
     *
     * @param $value
     */
    public function loadByValue($token_value)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM tokens WHERE value = :token_value",
            array('token_value' => $token_value)
        );

        if ($query) {
            $this->id = $query[0]['id'];
            $this->userId = $query[0]['user_id'];
            $this->purpose = $query[0]['purpose'];
            $this->value = $query[0]['value'];
            $this->datetime = $query[0]['datetime'];
        }
    }

    /**
     * Method for adding a new token into the database
     *
     * @return mixed
     */
    public function insert()
    {
        return $GLOBALS['db']->query(
            "INSERT INTO tokens (user_id, purpose, value, datetime)
            VALUES (:user_id, :token_purpose, :token_value, :token_time)
            ON DUPLICATE KEY UPDATE value = :token_value, datetime = :token_time",
            array(
                'user_id'       => $this->userId,
                'token_purpose' => $this->purpose,
                'token_value'   => $this->value,
                'token_time'    => $this->datetime
            )
        );
    }

    /**
     * Method for removing a token from the database
     *
     * @return mixed
     */
    public function remove()
    {
        return $GLOBALS['db']->query(
            "DELETE FROM tokens WHERE user_id = :user_id AND purpose = :token_purpose",
            array(
                'user_id'       => $this->userId,
                'token_purpose' => $this->purpose
            )
        );
    }

    /**
     * Set the token datetime
     *
     * @param $timestamp
     */
    public function setDatetime($timestamp)
    {
        $this->datetime = SysHelper::datetimeToString($timestamp);
    }

    /**
     * Check if token is still valid (true) or expired (false)
     *
     * @param int $lifetime
     * @return bool
     */
    public function checkValidity($lifetime = 7200)
    {
        return ((time() - strtotime($this->datetime)) < $lifetime)
            ? true
            : false;
    }

}