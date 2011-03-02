<?php

require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Model/ImbaMessage.php';
require_once 'Model/ImbaUser.php';

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
        $databaseresult = array();

        $query1 = "SELECT DISTINCT receiver as opponent FROM %s Where `sender` = '%s' order by `timestamp` DESC  LIMIT 0,3;";
        $this->database->query($query1, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openid
        ));

        while ($row = $this->database->fetchRow()) {
            array_push($databaseresult, $row["opponent"]);
        }

        $query2 = "SELECT DISTINCT sender   as opponent FROM %s Where `receiver` = '%s' order by `timestamp` DESC  LIMIT 0,3;";
        $this->database->query($query2, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openid
        ));

        while ($row = $this->database->fetchRow()) {
            array_push($databaseresult, $row["opponent"]);
        }

        $databaseresult = array_unique($databaseresult);

        $result = array();
        $managerUser = new ImbaManagerUser($this->database);
        foreach ($databaseresult as $value) {
            $user = new ImbaUser();
            $user = $managerUser->selectByOpenId($value);
            array_push($result, array("name" => $user->getNickname(), "openid" => $value));
        }

        return json_encode($result);
    }

    /**
     * Selects all new Messages for a user
     */
    public function selectNewMessagesByOpenid($openid) {
        $query = "SELECT sender FROM %s WHERE `receiver` =  '%s' AND new = 1";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MESSAGES, $openid));

        $result = array();
        while ($row = $this->database->fetchRow()) {
            array_push($result, $row["sender"]);
        }

        return json_encode($result);
    }

    // TODO: public function selectConversation($openid)
    // TODO: public function markRead($id)
}

?>
