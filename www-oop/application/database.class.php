<?php

namespace devlabs\app;

class database
{
    public $connection;

    public function connect($dbName, $dbUsername, $dbPassword)
    {
        try {
            $this->connection = new \PDO('mysql:host=localhost;dbname=' . $dbName, $dbUsername, $dbPassword);

            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            $this->connection = false;
        }
    }

    public function query($query, $bindings)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($bindings);
        //    $stmt->setFetchMode(\PDO::FETCH_OBJ);

        return ($stmt->rowCount() > 0) ? $stmt : false;
    }
}