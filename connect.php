<?php
class DatabaseHandler
{
	private $dsn;
	private $username;
	private $password;
	private $pdo;

	public function __construct($dsn, $username, $password)
	{
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
	}

	public function connect()
	{
		$this->pdo = new PDO($this->dsn, $this->username, $this->password);
	}

	public function disconnect()
	{
		$this->pdo = null;
	}

	public function executeQuery($sql, $attributes)
	{
		$statement = $this->pdo->prepare($sql);
		$success = $statement->execute($attributes);

		if (!$success)
		{
			$errorInfo = $statement->errorInfo();
			$errorMessage = $errorInfo[2];
			throw new PDOException($errorMessage);
		}
	}
}
