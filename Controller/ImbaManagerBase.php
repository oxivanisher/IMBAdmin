<?php

require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerLog.php';
require_once 'ImbaConfig.php';

/**
 * Base class for all Managers
 */
class ImbaManagerBase {
    
    /**
     * ImbaManagerDatabase
     */
    protected $database = null;
    
    /**
     * Ctor
    public function __construct() {
        $this->database = ImbaManagerDatabase::getInstance();
    }
     */
}

?>
