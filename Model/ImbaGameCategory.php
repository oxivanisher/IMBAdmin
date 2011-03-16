<?php

require_once 'Model/ImbaBase.php';

/**
 * Category for all Games managed by the IMBAdmin
 */
class ImbaGameCategory extends ImbaBase {

    /**
     * Fields
     */
    protected $name = null;

    /**
     * Properties
     */
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

}

?>
