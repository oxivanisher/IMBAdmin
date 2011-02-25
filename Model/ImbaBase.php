<?php
require_once 'Constants.php';

/**
 * Base class for all Models
 */
class ImbaBase {
	/**
 	* Field for Id
 	*/
	protected $Id;

	/**
 	* Property for Id
 	*/
	public function getId() {
		return $this -> Id;
	}

	public function setId($id) {
		$this -> Id = $id;
	}

}
?>