<?php

namespace Devlabs\App;

/**
 * Class database
 * @package devlabs\app
 */
class Database
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
            if (substr($query, 0, 6) === 'SELECT') {
                return $stmt->fetchAll();
            }

            return $stmt;
        }

        return array();
    }

    /**
     * Disable autocommit and begin transaction
     */
    public function startTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit the transaction and enable autocommit again
     */
    public function endTransaction()
    {
        $this->connection->commit();
    }
}
