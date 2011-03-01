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
     * Tries to send an insert command to the database.
     * Inserts a message into the Database if successfully.
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

        $query = "INSERT INTO %s ";
        $query .= "(sender, receiver, timestamp, subject, message, new, xmpp) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s','%s')";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $message->getSender(),
            $message->getReceiver(),
            $message->getTimestamp(),
            $message->getSubject(),
            $message->getMessage(),
            $message->getNew(),
            $message->getXmpp()
        ));
    }

    /**
     * Delets a message by Id
     */
    public function delete($id) {
        $query = "DELETE FROM  %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $id
        ));
    }

    /**
     * Select one message by id
     */
    public function selectById($id) {
        $query = "SELECT * FROM  %s Where id = '%s';";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $id
        ));
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

    /**
     * Selects a complete Conversation between two OpenIds
     */
    public function selectConversation($openidMe, $openidOpponent) {
        $query = "SELECT * FROM %s Where (sender = '%s' and receiver = '%s') or (sender = '%s' and receiver = '%s') order by timestamp DESC;";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openidMe,
            $openidOpponent,
            $openidOpponent,
            $openidMe
        ));

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

    /**
     * Selects the last Conversations of an User with OpenId
     */
    public function seletLastConversation($openid) {
        $query = "SELECT * FROM %s Where (sender = '%s' and receiver = '%s') or (sender = '%s' and receiver = '%s') order by timestamp DESC;";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openidMe,
            $openidOpponent,
            $openidOpponent,
            $openidMe
        ));
    }

    // TODO: public function selectConversation($openid)
    // TODO: public function markRead($id)
}

?>
