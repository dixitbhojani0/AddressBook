<?php
include_once __DIR__ . '/../Constants/Config.php';

class DatabaseConnection {

	public $serverName;
	public $userName;
	public $password;
	public $databaseName;
	public $connection;

	protected function __construct() {
		$this->connection = NULL;
		$this->serverName = DB_HOST;
		$this->userName = DB_USERNAME;
		$this->password = DB_PASSWORD;
		$this->databaseName = DB_NAME;

		$this->connection = mysqli_connect($this->serverName, $this->userName, $this->password, $this->databaseName);

		if (mysqli_connect_errno()) {
			die("Connection failed: " . mysqli_connect_error());
			exit;
		}
	}

	protected function close() {
		$this->connection && $this->connection->close();
	}
}
?>

