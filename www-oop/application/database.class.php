<?php

/**
 * Class database
 * @package devlabs\app
 */
namespace devlabs\app;

class database
{
    /**
     * A property for storing the database connection object
     */
    public $connection;

    /**
     * Method for establishing a connection to the database
     *
     * @param string $dbName
     * @param string $dbUsername
     * @param string $dbPassword
     */
    public function connect($dbName, $dbUsername, $dbPassword)
    {
        try {
            $this->connection = new \PDO('mysql:host=localhost;dbname=' . $dbName, $dbUsername, $dbPassword);

            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            $this->connection = false;
        }
    }

    /**
     * Method for executing a query to the database
     *
     * @param string $query
     * @param array $bindings
     * @return array
     */
    public function query($query, $bindings)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($bindings);
        //    $stmt->setFetchMode(\PDO::FETCH_OBJ);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return array();
    }
}
