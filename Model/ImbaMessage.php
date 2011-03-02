<?php

require_once 'Model/ImbaBase.php';

/**
 * Class for all messages
 *
 */
class ImbaMessage extends ImbaBase {

    /**
     * Fields
     */
    protected $sender = null;
    protected $receiver = null;
    protected $timestamp = null;
    protected $subject = null;
    protected $message = null;
    protected $new = null;
    protected $xmpp = null;

    public function getSender() {
        return $this->sender;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function getReceiver() {
        return $this->receiver;
    }

    public function setReceiver($receiver) {
        $this->receiver = $receiver;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getNew() {
        return $this->new;
    }

    public function setNew($new) {
        $this->new = $new;
    }

    public function getXmpp() {
        return $this->xmpp;
    }

    public function setXmpp($xmpp) {
        $this->xmpp = $xmpp;
    }

}

?>
