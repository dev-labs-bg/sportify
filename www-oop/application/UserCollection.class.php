<?php

namespace Devlabs\App;

/**
 * Class UserCollection
 * @package Devlabs\App
 */
class UserCollection
{
    private $allConfirmed;

    /**
     * Method for getting a list of all confirmed users from the database
     *
     *
     * @param string $email
     * @return mixed
     */
    public function getAllConfirmed($email = '')
    {
        $this->allConfirmed = array();

        $query = $GLOBALS['db']->query(
            "SELECT * FROM users WHERE confirmed = 1",
            array()
        );

        if ($query) {
            foreach ($query as &$row) {
                if ($row['email'] === $email) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }
                $this->allConfirmed[$row['id']] = new User(
                    $row['id'],
                    $row['first_name'],
                    $row['last_name'],
                    $row['email'],
                    $row['selected']
                );
            }
        }

        return $this->notScored;

    }
}