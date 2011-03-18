<?php

/**
 * Description of ImbaLogger
 */
class ImbaLogger {

    /**
     * ImbaManagerDatabase
     */
    protected $database = null;

    /**
     * Static logfunction to write into a file
     */
    public static function writeToLogFile($message, $ip) {
        $myFile = "Logs/ImbaLog.log";
        $handler = fopen($myFile, 'a+');
        if ($handler) {
            $stringData = date("Y-d-m H:i:s") . " ($ip): " . $message . "\n";
            fwrite($handler, $stringData);
            fclose($handler);
        }
    }

    /**
     * Ctor
     */
    public function __construct(ImbaManagerDatabase $database) {
        $this->database = $database;
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
