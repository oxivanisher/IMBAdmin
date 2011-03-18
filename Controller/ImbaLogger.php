<?php

require_once 'Model/ImbaLog.php';
require_once 'Controller/ImbaManagerBase.php';

/**
 * Description of ImbaLogger
 */
class ImbaLogger extends ImbaManagerBase {

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
        $log = new ImbaLog();
        $log->timestamp = time();
        $log->ip = ImbaSharedFunctions::getIP();
        $log->session = session_id();
        $log->user = ImbaUserContext::getOpenIdUrl();

        return $log;
    }

    /*
     * Inserts a Systemmessage / Log
     */

    public function insert(ImbaLog $log) {
        $query = "INSERT INTO %s ";
        $query .= "(timestamp, user, ip, module, session, msg, lvl) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s', '%s')";

        $database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES,
            $log->getTimestamp(),
            $log->getUser(),
            $log->getIp(),
            $log->getModule(),
            $log->getSession(),
            $log->getMessage(),
            $log->getLevel()
        ));
    }

    public function getAll() {
        $query = "SELECT * FROM %s WHERE 1;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES));

        $messages = array();
        while ($row = $this->database->fetchRow()) {
            $log = new ImbaLog();
            $log->setId($row["id"]);
            $log->setTimestamp($row["timestamp"]);
            $log->setUser($row["user"]);
            $log->setIp($row["ip"]);
            $log->setModule($row["module"]);
            $log->setSession($row["session"]);
            $log->setMessage($row["msg"]);
            $log->setLevel($row["lvl"]);
            
            array_push($messages, $log);
            unset($log);
        }
        
        return $messages;
    }

}

?>
