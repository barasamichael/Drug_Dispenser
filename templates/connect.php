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

		return $success;
	}

	public function selectQuery($sql, $attributes = [])
	{
		try
		{
			if (!empty($attributes))
			{
				$statement = $this->pdo->prepare($sql);
				if (!$statement)
				{
					$errorInfo = $this->pdo->errorInfo();
					throw new Exception("Error preparing the statement: " . $errorInfo[2]);
				}
				if (!$statement->execute($attributes))
				{
					$errorInfo = $statement->errorInfo();
					throw new Exception("Error executing the prepared statement: " . $errorInfo[2]);
				}
				return $statement->fetchAll();
			}
			else
			{
				$result = $this->pdo->query($sql);
				if (!$result)
				{
					$errorInfo = $this->pdo->errorInfo();
					throw new Exception("Error executing the query: " . $errorInfo[2]);
				}
				return $result->fetchAll();
			}
		}
		catch (Exception $e)
		{
			echo "Exception occurred: " . $e->getMessage();
		}
		return [];
	}
}
