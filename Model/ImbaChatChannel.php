<?php

require_once 'Model/ImbaBase.php';

/**
 * Chat Channels
 */
class ImbaChatChannel extends ImbaBase {

    /**
     * Fields
     */
    protected $owner = null;
    protected $name = null;
    protected $allowed = null;
    protected $created = null;
    protected $lastmessage = null;

    /**
     * Properties
     */
    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getAllowed() {
        return $this->allowed;
    }

    public function setAllowed($allowed) {
        $this->allowed = $allowed;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getLastmessage() {
        return $this->lastmessage;
    }

    public function setLastmessage($lastmessage) {
        $this->lastmessage = $lastmessage;
    }

}

?>
