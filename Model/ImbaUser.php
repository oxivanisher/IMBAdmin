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
	protected $birthmonth;
	protected $birthyear;
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
	// deleted protected $armorychars; oxi, 25.02.2011

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

	public function getEmail() {
		return $this -> email;
	}

	public function setEmail($email) {
		$this -> email = $email;
	}

	public function getFirstname() {
		return $this -> $firstname;
	}

	public function setFirstname($firstname) {
		$this -> $firstname = $firstname;
	}

	public function getLastname() {
		return $this -> lastname;
	}

	public function setLastname($lastname) {
		$this -> lastname = $lastname;
	}

	public function getBirthday() {
		return $this -> birthday;
	}

	public function setBirthday($birthday) {
		$this -> birthday = $birthday;
	}

	public function getBirthmonth() {
		return $this -> birthmonth;
	}

	public function setBirthmonth($birthmonth) {
		$this -> birthmonth = $birthmonth;
	}

	public function getBirthyear() {
		return $this -> birthyear;
	}

	public function setBirthyear($birthyear) {
		$this -> birthyear = $birthyear;
	}

	public function getSex() {
		return $this -> sex;
	}

	public function setSex($sex) {
		$this -> sex = $sex;
	}

	public function getIcq() {
		return $this -> icq;
	}

	public function setIcq($icq) {
		$this -> icq = $icq;
	}

	public function getMsn() {
		return $this -> msn;
	}

	public function setMsn($msn) {
		$this -> msn = $msn;
	}

	public function getSkype() {
		return $this -> skype;
	}

	public function setSkype($skype) {
		$this -> skype = $skype;
	}

	public function getUsertitle() {
		return $this -> usertitle;
	}

	public function setUsertitle($usertitle) {
		$this -> usertitle = $usertitle;
	}

	public function getAvatar() {
		return $this -> avatar;
	}

	public function setAvatar($avatar) {
		$this -> avatar = $avatar;
	}

	public function getSignature() {
		return $this -> signature;
	}

	public function setSignature($signature) {
		$this -> signature = $signature;
	}

	public function getWebsite() {
		return $this -> website;
	}

	public function setWebsite($website) {
		$this -> website = $website;
	}

	public function getMotto() {
		return $this -> motto;
	}

	public function setMotto($motto) {
		$this -> motto = $motto;
	}

	public function getRole() {
		return $this -> role;
	}

	public function setRole($role) {
		$this -> role = $role;
	}

	public function getAccurate() {
		return $this -> accurate;
	}

	public function setAccurate($accurate) {
		$this -> accurate = $accurate;
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
		$this -> email = $result["email"];
		$this -> firstname = $result["firstname"];
		$this -> lastname = $result["lastname"];
		$this -> birthday = $result["birthday"];
		$this -> birthmonth = $result["birthmonth"];
		$this -> birthyear = $result["birthyear"];
		$this -> sex = $result["sex"];
		$this -> icq = $result["icq"];
		$this -> msn = $result["msn"];
		$this -> skype = $result["skype"];
		$this -> usertitle = $result["usertitle"];
		$this -> avatar = $result["avatar"];
		$this -> signature = $result["signature"];
		$this -> website = $result["website"];
		$this -> motto = $result["motto"];
		$this -> accurate = $result["accurate"];
		$this -> role = $result["role"];
	}

}
?>