<?php namespace App\DB;

$config = array(
	'username' => 'root',
	'password' => 'P@ssw0rd',
	'database' => 'dev_sportify'
);

function connect($config)
{
	try {
		$conn = new \PDO('mysql:host=localhost;dbname=' . $config['database'],
						$config['username'],
						$config['password']);

		$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $conn;
	} catch(Exception $e) {
		return false;
	}
}

function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);
	$stmt->execute($bindings);
//    $stmt->setFetchMode(\PDO::FETCH_OBJ);

	return ($stmt->rowCount() > 0) ? $stmt : false;
}

function get_table($tableName, $conn, $limit = 10)
{
		$stmt = $query("SELECT * FROM $table ORDER BY id DESC LIMIT $limit", array('table' => $tableName), $conn);
//		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$result = $stmt->fetchAll();

		return ( $result->rowCount() > 0 ) ? $result : false;
}

