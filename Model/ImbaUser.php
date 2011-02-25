<?php
require_once 'ImbaBase.php';

/**
 *  Class for all Users
 */
class ImbaUser extends ImbaBase {
	/**
 	* Fields
 	*/
	protected $openId;
	protected $nickname;
	protected $email;
	protected $firstname;
	protected $lastname;
	protected $birthday;
	protected $sex;
	protected $icq;
	protected $msn;
	protected $skype;
	protected $usertitle;
	protected $avatar;
	protected $signature;
	protected $website;
	protected $motto;
	protected $accurate;
	protected $role;
	protected $armorychars;

	/**
 	* Properties
 	*/
	public function getOpenId() {
		return $this -> openId;
	}

	public function setOpenId($openId) {
		$this -> openId = $openId;
	}

	public function getNickname() {
		return $this -> nickname;
	}

	public function setNickname($nickname) {
		$this -> nickname = $nickname;
	}

	// TODO: Setter und Getter der Fields ergänzen

	/**
 	* Methods
 	*/
	public function loadByOpenId($imbaDatabaseManager, $openId) {
		$query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES . " Where openid = '" . $openId . "';";
		$imbaDatabaseManager -> query($query);
		$result = $imbaDatabaseManager -> fetchRow();

		$this -> openId = $openId;
		$this -> nickname = $result["nickname"];
		// TODO: Setzen der Felder ergänzen
	}

}
?>