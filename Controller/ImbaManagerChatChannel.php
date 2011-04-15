<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaChatChannel.php';

// $DATABASE_TABLES_CHAT_CHATCHANNELS
// $DATABASE_TABLES_CHAT_CHATMESSAGES

/**
 *  Controller / Manager for Chat Channels
 *  - insert, update, delete, join, leave
 */
class ImbaManagerChatChannel extends ImbaManagerBase {

    /**
     * Property
     */
    protected $chatChannelsCached = null;
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
     * Inserts a Channel
     */
    public function insert(ImbaChatChannel $channel) {
        $query = "INSERT INTO %s (name, allowed, created, lastmessage) VALUES ('%s', '%s', '%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_CHAT_CHATCHANNELS,
            $channel->getName(),
            $channel->getAllowed(),
            date("U"),
            "0"
        ));

        $this->chatChannelsCached = null;
    }

    /**
     * Select all Channels for logged in User
     */
    public function selectAll() {
        if ($this->chatChannelsCached == null) {
            $result = array();

            $query = "SELECT * FROM %s ORDER BY name ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_CHAT_CHATCHANNELS));
            while ($row = $this->database->fetchRow()) {
                $channel = new ImbaChatChannel();
                $channel->setId($row["id"]);
                $channel->setName($row["name"]);
                $channel->setLastmessage($row["lastmessage"]);
                $channel->setAllowed($row["allowed"]);

                // Check if my Role is allowed:
                $allowed = json_decode($channel->getAllowed(), true);

                $amIallowed = false;
                foreach ($allowed as $a) {
                    if ($a["allowed"] == true && $a["role"] == ImbaUserContext::getUserRole()) {
                        $amIallowed = true;
                    }
                }

                if ($amIallowed) {
                    array_push($result, $channel);
                }
            }

            $this->chatChannelsCached = $result;
        }

        return $this->chatChannelsCached;
    }

    /**
     * Get a new Game
     */
    public function getNew() {
        return new ImbaChatChannel();
    }

    /**
     * Select one Channel by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $channel) {
            if ($channel->getId() == $id)
                return $channel;
        }
        return null;
    }

}

?>
