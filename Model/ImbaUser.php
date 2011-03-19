<?php

require_once 'ImbaConstants.php';
require_once 'Model/ImbaBase.php';

/**
 *  Class for all Users
 */
class ImbaUser extends ImbaBase {

    /**
     * Fields
     */
    protected $openId = null;
    protected $nickname = null;
    protected $email = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $birthday = null;
    protected $birthmonth = null;
    protected $birthyear = null;
    protected $sex = null;
    protected $icq = null;
    protected $msn = null;
    protected $skype = null;
    protected $usertitle = null;
    protected $avatar = null;
    protected $signature = null;
    protected $website = null;
    protected $motto = null;
    protected $accurate = null;
    protected $role = null;
    protected $lastonline = null;
    // deleted protected $armorychars; oxi, 25.02.2011

    /**
     * Properties
     */
    public function getOpenId() {
        return $this->openId;
    }

    public function setOpenId($openId) {
        $this->openId = $openId;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getBirthday() {
        if (count($this->birthday) == 1) $this->birthday = "0" . $this->birthday;
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getBirthmonth() {
        if (count($this->birthmonth) == 1) $this->birthmonth = "0" . $this->birthmonth;
        return $this->birthmonth;
    }

    public function setBirthmonth($birthmonth) {
        $this->birthmonth = $birthmonth;
    }

    public function getBirthyear() {
        return $this->birthyear;
    }

    public function setBirthyear($birthyear) {
        $this->birthyear = $birthyear;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex) {
        $this->sex = $sex;
    }

    public function getIcq() {
        return $this->icq;
    }

    public function setIcq($icq) {
        $this->icq = $icq;
    }

    public function getMsn() {
        return $this->msn;
    }

    public function setMsn($msn) {
        $this->msn = $msn;
    }

    public function getSkype() {
        return $this->skype;
    }

    public function setSkype($skype) {
        $this->skype = $skype;
    }

    public function getUsertitle() {
        return $this->usertitle;
    }

    public function setUsertitle($usertitle) {
        $this->usertitle = $usertitle;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function getSignature() {
        return $this->signature;
    }

    public function setSignature($signature) {
        $this->signature = $signature;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }

    public function getMotto() {
        return $this->motto;
    }

    public function setMotto($motto) {
        $this->motto = $motto;
    }

    public function getAccurate() {
        return $this->accurate;
    }

    public function setAccurate($accurate) {
        $this->accurate = $accurate;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getLastonline() {
        return $this->lastonline;
    }

    public function setLastonline($lastonline) {
        $this->lastonline = $lastonline;
    }

}

?>