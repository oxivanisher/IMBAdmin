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
        if ($message->getMessage() == null || $message->getMessage() == "") {
            throw new Exception("No Message!");
        }
        if ($message->getSender() == null || $message->getSender() == "") {
            throw new Exception("No Sender!");
        }

        if ($message->getReceiver() == null || $message->getReceiver() == "") {
            throw new Exception("No Reciever!");
        } 

        $query = "INSERT INTO " . ImbaConstants::$DATABASE_TABLES_USR_MESSAGES . " ";
        $query .= "(sender, receiver, timestamp, subject, message, new, xmpp) VALUES ";
        $query .= "('" . $message->getSender() . "', '" . $message->getReceiver() . "', '" . $message->getTimestamp() . "', '" . $message->getSubject() . "', '" . $message->getMessage() . "', '" . $message->getNew() . "', '" . $message->getXmpp() . "')";
        $this->database->query($query);
    }

    /**
     * Delets a message by Id
     */
    public function delete($id) {
        $query = "DELETE FROM  " . ImbaConstants::$DATABASE_TABLES_USR_MESSAGES . " Where id = '" . $id . "';";
        $this->database->query($query);
    }

    /**
     * Select one message by id
     */
    public function selectById($id) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_USR_MESSAGES . " Where id = '" . $id . "';";

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

    public function selectConversation($openidMe, $openidOpponent) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_USR_MESSAGES . " Where (sender = '$openidMe' and receiver = '$openidOpponent') or (sender = '$openidOpponent' and receiver = '$openidMe') order by timestamp DESC;";
        $this->database->query($query);

        $result = new ArrayObject();
        while ($row = $this->database->fetchRow()) {
            $message = new ImbaMessage();
            $message->setId($row["id"]);
            $message->setSender($row["sender"]);
            $message->setReceiver($row["receiver"]);
            $message->setTimestamp($row["timestamp"]);
            $message->setSubject($row["subject"]);
            $message->setMessage($row["message"]);
            $message->setNew($row["new"]);
            $message->setXmpp($row["xmpp"]);
            $result->append($message);
        }

        return $result;
    }

    // TODO: public function selectConversation($openid)
    // TODO: public function markRead($id)
}

?>
