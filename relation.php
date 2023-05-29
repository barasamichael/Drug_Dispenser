<?php

include_once 'connect.php';

class Relation
{
	protected $__tablename__ = null;

	protected function __construct(...$kwargs)
	{
		foreach ($kwargs[0] as $key => $value)
		{
			$this->_setAttribute($key, $value);
		}
	}

	private function _getAttribute($attributeName)
	{
		$reflection = new ReflectionClass($this);
		if ($reflection->hasProperty($attributeName))
		{
			$property = $reflection->getProperty($attributeName);
			$property->setAccessible(true);
			return $property->getValue($this);
		}
		else
		{
			return null;
		}
	}

	private function _setAttribute($attributeName, $attributeValue)
	{
		$reflection = new ReflectionClass($this);
		if ($reflection->hasProperty($attributeName))
		{
			$property = $reflection->getProperty($attributeName);
			$property->setAccessible(true);
			$property->setValue($this, $attributeValue);
		}
		else
		{
			return null;
		}
	}

	public function getAttribute($attributeName)
	{
		return $this->_getAttribute($attributeName);
	}

	public function setAttribute($attributeName, $attributeValue)
	{
		$this->_setAttribute($attributeName, $attributeValue);
	}

	private function _addToDatabase()
	{
		// Create a clone of calling object
		$reflection = new ReflectionClass($this);

		$dsn = 'mysql:host=localhost; dbname=drugs_db';
		$username = 'root';
		$password = 'MySQLXXX-123a8910';

		try
		{
			$dbHandler = new DatabaseHandler($dsn, $username, $password);
			$dbHandler->connect();
			$attributes = [];

			// Retrieve attributes and their values
			foreach ($reflection->getProperties() as $property)
			{
				$propertyName = $property->getName();
				$property->setAccessible(true);
				$propertyValue = $property->getValue($this);

				# Only for attributes assigned values to them
				if (!empty($propertyValue) || $propertyValue === 0)
				{
					$attributes[$propertyName] = $propertyValue;
				}
			}
			
			// Remove special properties from the attributes array
			unset($attributes['__tablename__']);

			// Generate the SQL INSERT query statement
			$columns = implode(', ', array_keys($attributes));
			$placeholders = ':' . implode(', :', array_keys($attributes));
			$SQL = "INSERT INTO {$this->__tablename__} ($columns) VALUES " . 
				"($placeholders)";

			// Execute the query
			$success = $dbHandler->executeQuery($SQL, $attributes);
			$dbHandler->disconnect();

			if ($success)
			{
				return $success;
			}
			else
			{
				$errorInfo = $statement->errorInfo();
				$errorMessage = $errorInfo[2];
				throw new PDOException($errorMessage);
			}

			// close the database connection
			$pdo = null;

			// Operation successfull :)
			return $success;
		}
		catch (PDOException $error)
		{
			echo "Error occured while saving data: " . $error->getMessage();

			// Operation failed :(
			return false;
		}
	}

	public function save()
	{
		return $this->_addToDatabase();
	}
}
?>
