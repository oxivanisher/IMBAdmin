<?php

require_once 'Model/ImbaLog.php';
require_once 'Controller/ImbaManagerBase.php';

/**
 * Description of ImbaLogger
 */
class ImbaLogger extends ImbaManagerBase {

    /**
     * Singleton implementation
     */
    private static $instance = NULL;

    /**
     * Ctor
     */
    protected function __construct() {
        //parent::__construct();
        $this->database = ImbaManagerDatabase::getInstance();
    }

    /*
     * Singleton init
     */

    public static function getInstance() {
        if (self::$instance === NULL)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Create new log entry
     */
    public function getNew() {
        $log = new ImbaLog();
        $log->setTimestamp(time());
        $log->setIp(ImbaSharedFunctions::getIP());
        $log->setSession(session_id());
        $log->setUser(ImbaUserContext::getOpenIdUrl());

        return $log;
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
        $query = "SELECT * FROM %s WHERE 1 ORDER BY id DESC;";
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

    public function getId($id) {
        $query = "SELECT * FROM %s WHERE id='%s' ORDER BY id DESC;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES), $id);

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

    public function getSession($session) {
        $query = "SELECT * FROM %s WHERE session='%s' ORDER BY id DESC;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES), $session);

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

    public function getUserSessions() {
        $query = "SELECT * FROM %s WHERE message='Logged in' ORDER BY id DESC;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES), $session);

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
