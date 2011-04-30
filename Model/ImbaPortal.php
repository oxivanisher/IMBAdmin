<?php

require_once 'Model/ImbaBase.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * Description of ImbaPortal
 */
class ImbaPortal extends ImbaBase {

    protected $name = null;
    protected $icon = null;
    protected $aliases = array();
    protected $portalEntries = array();
    protected $comment = null;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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

    public function getAliases() {
        return $this->aliases;
    }

    public function setAliases($aliases) {
        $this->aliases = $aliases;
    }
    
    public function addAlias($alias){
        array_push($this->aliases, $alias);
    }

    public function getPortalEntries() {
        return $this->portalEntries;
    }

    public function setPortalEntries($portalEntries) {
        $this->portalEntries = $portalEntries;
    }

    public function addEntry($entry) {
        array_push($this->portalEntries, $entry);
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

}

?>
