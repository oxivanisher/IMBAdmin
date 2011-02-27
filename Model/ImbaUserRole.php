<?php

require_once 'Constants.php';
require_once 'Model/ImbaBase.php';

/**
 *  Class for Userroles
 */
class ImbaUserRole extends ImbaBase {

    /**
     * Fields
     */
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
        return $this->icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * Methods
     */
    public function loadById($imbaDatabaseManager, $id) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_PROFILES . " Where id = '" . $id . "';";
        $imbaDatabaseManager->query($query);
        $result = $imbaDatabaseManager->fetchRow();

        $this->setId($id);
        $this->setHandle($result["handle"]);
        $this->setRole($result["role"]);
        $this->setName($result["name"]);
        $this->setSmf($result["smf"]);
        $this->setWordpress($result["wordpress"]);
        $this->setIcon($result["icon"]);
    }

}

?>