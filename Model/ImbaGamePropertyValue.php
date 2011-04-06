<?php

require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaGameProperty.php';
require_once 'Model/ImbaBase.php';

/**
 * Class for an value of a ImbaGameProerty
 */
class ImbaGamePropertyValue extends ImbaBase {

    /**
     * Fields
     */
    protected $user = null;
    protected $property = null;
    protected $value = null;

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getProperty() {
        return $this->property;
    }

    public function setProperty($property) {
        $this->property = $property;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

}

?>
