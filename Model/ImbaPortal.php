<?php

require_once 'Model/ImbaBase.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * Description of ImbaPortal
 */
class ImbaPortal extends ImbaBase {

    protected $name = null;
    protected $icon = null;
    protected $aliases = null;
    protected $navitems = null;
    protected $comment = null;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getIcon() {
        return $this->icon;
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

    public function getNavitems() {
        return $this->navitems;
    }

    public function setNavitems($navitems) {
        $this->navitems = $navitems;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

}

?>
