<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerUser.php';
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

    /**
     * Selects all Messages in a Channel having the smalles id be $since
     * $since > 0 => since that id
     * $since == 0 => all Messages
     * $since == -1 => give me the last 10 Messages
     */
    public function selectAllByChannel(ImbaChatChannel $channel, $since = 0) {
        /**
         * if $lines is 0, return all messages
         */
        if ($since == 0) {
            $tmpIdSince = "";
            $tmpLimit = "";
        } else if ($since == -1) {
            $tmpIdSince = "";
            $tmpLimit = " LIMIT 0, 10 ";
        } else {
            $tmpIdSince = " AND id > '$since'";
            $tmpLimit = "";
        }

        $query = "SELECT * FROM %s WHERE channel = '%s' " . $tmpIdSince . " ORDER BY timestamp DESC, id DESC " . $tmpLimit . ";";

        // init all user
        ImbaManagerUser::getInstance()->selectAll();

        $result = array();
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_CHAT_CHATMESSAGES, $channel->getId()));
        while ($row = $this->database->fetchRow()) {
            $message = new ImbaChatMessage();
            $message->setId($row["id"]);
            $message->setMessage($row["message"]);
            $message->setTimestamp($row["timestamp"]);
            $message->setChannel($channel);
            $message->setSender(ImbaManagerUser::getInstance()->selectById($row["sender"]));
            array_push($result, $message);
        }

        return $result;
    }

    /**
     * Inserts a ImbaChatMessage into the Database
     */
    public function insert(ImbaChatMessage $message) {
        $query = "INSERT INTO %s (sender, channel, timestamp, message) VALUES ('%s', '%s', '%s', '%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_CHAT_CHATMESSAGES,
            ImbaUserContext::getUserId(),
            $message->getChannel()->getId(),
            date("U"),
            $message->getMessage()
        ));
    }

}

?>
