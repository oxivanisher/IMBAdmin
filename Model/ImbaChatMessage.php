<?php

require_once 'Model/ImbaBase.php';

/**
 * Chat Message
 */
class ImbaChatMessage extends ImbaBase {

    /**
     * Fields
     */
    protected $sender = null;
    protected $channel = null;
    protected $timestamp = null;
    protected $message = null;

    /**
     * Properties
     */
    public function getSender() {
        return $this->sender;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function getChannel() {
        return $this->channel;
    }

    public function setChannel($channel) {
        $this->channel = $channel;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

}

?>
