<?php
/**
 * Load dependencies
 */
require_once "ImbaConstants.php";

/**
 * Controller / Manager Database
 * Handling:
 * - Connection
 * - Fetch
 * - Query
 */
class ImbaManagerDatabase {

    private $connection = NULL;
    /**
     * Recent response from sql server.
     */
    private $result = NULL;
    /**
     * Number of rows afflicted by the recent successful query send.
     */
    private $counter = NULL;
    /**
     * Singleton implementation.
     * 
     * @var type singleton object.
     */
    private static $instance = NULL;
    
    private function __construct($host, $database, $user, $pass) {
        /**
         * Setting the local Timezone
         */
        setlocale(ImbaConstants::$CONTEXT_LOCALE[0], ImbaConstants::$CONTEXT_LOCALE[1], ImbaConstants::$CONTEXT_LOCALE[2], ImbaConstants::$CONTEXT_LOCALE[3], ImbaConstants::$CONTEXT_LOCALE[4]
        );

        $this->connection = mysql_pconnect($host, $user, $pass, TRUE);
        mysql_query('set character set utf8;');
        mysql_set_charset('UTF8', $this->connection);

        if (!mysql_select_db($database, $this->connection)) {
            throw new Exception("Database Connection not working!");
        }
    }

    public static function getInstance($host, $database, $user, $pass) {
        if (self::$instance === NULL)
            self::$instance = new self($host, $database, $user, $pass);
        return self::$instance;
    }

    public function disconnect() {
        if (is_resource($this->connection))
            mysql_close($this->connection);
    }

    public function query($queryStr, array $args = array()) {
        foreach ($args as $key => $value) {
            $args[$key] = mysql_real_escape_string($value);
        }
        $query = vsprintf($queryStr, $args);

        $this->result = mysql_query($query, $this->connection);

        //echo $query;

        if (!$this->result) {
            throw new Exception("Database Query not working!");
        }

        $this->counter = NULL;
    }

    public function fetchRow() {
        return mysql_fetch_assoc($this->result);
    }

    public function count() {
        if ($this->counter == NULL && is_resource($this->result)) {
            $this->counter = mysql_num_rows($this->result);
        }

        return $this->counter;
    }

}

?>