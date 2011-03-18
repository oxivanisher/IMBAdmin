<?php

require_once 'Model/ImbaLog.php';
require_once 'Controller/ImbaManagerBase.php';


/**
 * Description of ImbaLogger
 */
class ImbaLogger extends ImbaManagerBase {

    /**
     * ImbaManagerDatabase
     */
    protected $database = null;

    /**
     * Ctor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Create new log entry
     */
    public function getNew() {
        return new ImbaLog();
    }


    /*
     * Inserts a Systemmessage / Log
     */    
    public function insert(ImbaLog $log) {
        $query = "INSERT INTO %s ";
        $query .= "(timestamp, user, ip, module, session, msg, lvl) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s', '%s')";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES,
            date("U"),
            $log->getUser(),
            $log->getIp(),
            $log->getModule(),
            $log->getSession(),
            $log->getMessage(),
            $log->getLevel()
        ));
    }    
}

?>
