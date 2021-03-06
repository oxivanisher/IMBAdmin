<?php

require_once 'ImbaConstants.php';
require_once 'Model/ImbaBase.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 *  Class for Userroles
 */
class ImbaUserRole extends ImbaBase {

    /**
     * Fields
     */
    protected $id;
    protected $handle;
    protected $role;
    protected $name;
    protected $smf;
    protected $wordpress;
    protected $icon;

    /**
     * Properties
     */
    public function getHandle() {
        return $this->handle;
    }

    public function setHandle($handle) {
        $this->handle = $handle;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSmf() {
        return $this->smf;
    }

    public function setSmf($smf) {
        $this->smf = $smf;
    }

    public function getWordpress() {
        return $this->wordpress;
    }

    public function setWordpress($wordpress) {
        $this->wordpress = $wordpress;
    }

    public function getIcon() {
        if ($this->icon == null || $this->icon == "") {
            return ImbaSharedFunctions::fixWebPath("Images/noicon.png");
        } else {
            return $this->icon;
        }
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

}

?>