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

    /**
     * Ctor
     */
    public function __construct() {
        $this->timestamp = time();
        $this->ip = ImbaSharedFunctions::getIP();
        $this->session = $_SESSION["hash"];
        $this->user = ImbaUserContext::getOpenIdUrl();
        
    }

    public function setModule($module) {
        $this->module = $module;
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
