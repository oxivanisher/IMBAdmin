<?php

require_once 'Model/ImbaBase.php';

/**
 * Class for ongoing auth requests
 */
class ImbaAuthRequest extends ImbaBase {

    /**
     * Fields
     */
    protected $hash = null;
    protected $userId = null;
    protected $timestamp = null;
    protected $realm = null;
    protected $returnTo = null;
    protected $type = null;
    protected $domain = null;
    protected $phpsession = null;

    /**
     * Properties
     */
    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getRealm() {
        return $this->realm;
    }

    public function setRealm($realm) {
        $this->realm = $realm;
    }

    public function getReturnTo() {
        return $this->returnTo;
    }

    public function setReturnTo($returnTo) {
        $this->returnTo = $returnTo;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getPhpsession() {
        return $this->phpsession;
    }

    public function setPhpsession($phpsession) {
        $this->phpsession = $phpsession;
    }

}

?>
