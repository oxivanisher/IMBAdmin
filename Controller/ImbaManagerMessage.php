<?php

require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaMessage.php';

/**
 * Description of ImbaManagerMessage
 *
 */
class ImbaManagerMessage {
    /**
     * ImbaManagerDatabase
     */
    protected $database = null;

    /**
     * Ctor
     */
    public function __construct(ImbaManagerDatabase $database) {
        $this->database = $database;
    }

    /**
     * Inserts a message into the Database
     */
    public function insert(ImbaMessage $message) {
        $query = "INSERT INTO " . ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES . " ";
        $query .= "(sender, receiver, timestamp, subject, message, new, xmpp) VALUES ";
        $query .= "('" . $message->getSender() . "', '" . $message->getReceiver() . "', '" . $message->getTimestamp() . "', '" . $message->getSubject() . "', '" . $message->getMessage() . "', '" . $message->getNew() . "', '" . $message->getXmpp() . "')";
        $this->database->query($query);
    }

    /**
     * Delets a message by Id
     */
    public function delete($id) {
        $query = "DELETE FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES . " Where id = '" . $id . "';";
        $this->database->query($query);
    }

    /**
     * Select one message by id
     */
    public function selectById($id) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_SYSTEMMESSAGES . " Where id = '" . $id . "';";

        $this->database->query($query);
        $result = $this->database->fetchRow();

        $message = new ImbaMessage();
        $message->setOpenId($id);
        $message->setSender($result["sender"]);
        $message->setReceiver($result["receiver"]);
        $message->setTimestamp($result["timestamp"]);
        $message->setSubject($result["subject"]);
        $message->setMessage($result["message"]);
        $message->setNew($result["new"]);
        $message->setXmpp($result["xmpp"]);
        return $message;
    }

    
    // TODO: public function selectConversation($openid)
    // TODO: public function markRead($id)
    
}

?>
