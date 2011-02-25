<?php

/**
 * Controller / Manager Database
 * Handling:
 * - Connection
 * - Fetch
 * - Query
 */
class ManagerDatabase {
	private $connection = NULL;
	private $result = NULL;
	private $counter = NULL;

	public function __construct($host=NULL, $database=NULL, $user=NULL, $pass=NULL) {
		$this -> connection = mysql_pconnect($host, $user, $pass, TRUE);

		if(!mysql_select_db($database, $this -> connection)) {
			throw new Exception("Database Connection not working!");
		}
	}

	public function disconnect() {
		if(is_resource($this -> connection))
			mysql_close($this -> connection);
	}

	public function query($query) {
		$this -> result = mysql_query($query, $this -> connection);

		if(!$this -> result) {
			throw new Exception("Database Query not working!");
		}

		$this -> counter = NULL;
	}

	public function fetchRow() {
		return    mysql_fetch_assoc($this -> result);
	}

	public function count() {
		if($this -> counter == NULL && is_resource($this -> result)) {
			$this -> counter = mysql_num_rows($this -> result);
		}

		return $this -> counter;
	}

}
?>