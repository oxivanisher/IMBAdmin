<?php

require_once 'Model/ImbaBase.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * Description of ImbaLog
 */
class ImbaLog extends ImbaBase {

    protected $timestamp = null;
    protected $user = null;
    protected $ip = null;
    protected $module = null;
    protected $session = null;
    protected $message = null;
    protected $level = null;

    public function setModule($module) {
        $this->module = $module;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function setSession($session) {
        $this->session = $session;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function getUser() {
        return $this->user;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getModule() {
        return $this->module;
    }

    public function getSession() {
        return $this->session;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getLevel() {
        return $this->level;
    }

}

?>
