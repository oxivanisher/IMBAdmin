<?php

require_once 'Controller/ImbaManagerDatabase.php';
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
     */
    public function __construct() {
        $this->database = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    }
}

?>
