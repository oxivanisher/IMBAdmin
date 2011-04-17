<?php

require_once 'Model/ImbaBase.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * Description of ImbaPortalEntry
 */
class ImbaPortalEntry extends ImbaBase {

    protected $handle = null;
    protected $name = null;
    protected $target = null;
    protected $url = null;
    protected $comment = null;
    protected $loggedin = null;
    protected $role = null;
    
    public function getHandle() {
        return $this->handle;
    }

    public function setHandle($handle) {
        $this->handle = $handle;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getTarget() {
        return $this->target;
    }

    public function setTarget($target) {
        $this->target = $target;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getLoggedin() {
        return $this->loggedin;
    }

    public function setLoggedin($loggedin) {
        $this->loggedin = $loggedin;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}

?>
