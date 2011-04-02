<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaChatMessage.php';

// $DATABASE_TABLES_CHAT_CHATCHANNELS
// $DATABASE_TABLES_CHAT_CHATMESSAGES

/**
 *  Controller / Manager for Chat Messages
 *  - insert, delete, select
 */
class ImbaManagerChatMessage extends ImbaManagerBase {

    /**
     * Property
     */
    protected $chatMessagesCached = null;
    /**
     * Singleton implementation
     */
    private static $instance = null;

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
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

}

?>
