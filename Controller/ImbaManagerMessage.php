<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Model/ImbaMessage.php';
require_once 'Model/ImbaUser.php';

/**
 * Description of ImbaManagerMessage
 */
class ImbaManagerMessage extends ImbaManagerBase {

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
     * Tries to send an insert command to the database.
     * Inserts a message into the Database if successfully.
     */
    public function insert(ImbaMessage $message) {
        if ($message->getMessage() == null || trim($message->getMessage()) == "") {
            throw new Exception("No Message!");
        }
        if ($message->getSender() == null || trim($message->getSender()) == "") {
            throw new Exception("No Sender!");
        }

        if ($message->getReceiver() == null || trim($message->getReceiver()) == "") {
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
     * Get num of messages
     */
    public function returnNumberOfMessages() {
        $query = "SELECT * FROM  %s Where 1;";

        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MESSAGES));
        return $this->database->count();
    }

    /**
     * Selects a complete Conversation between two OpenIds
     */
    public function selectConversation($openidMe, $openidOpponent, $lines = 10) {
        $query = "SELECT * FROM %s Where (sender = '%s' and receiver = '%s') or (sender = '%s' and receiver = '%s') order by timestamp DESC, id DESC LIMIT 0, %s;";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openidMe,
            $openidOpponent,
            $openidOpponent,
            $openidMe,
            $lines
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
     * Selects the count of lines Conversation between two OpenIds
     */
    public function selectMessagesCount($openidMe, $openidOpponent) {
        // TODO: Nur die zÃ¤hlen, die in den letzten monaten geschlumpft wurden
        $query = "SELECT count(*) MsgCount FROM %s Where (sender = '%s' and receiver = '%s') or (sender = '%s' and receiver = '%s') AND timestamp > (" . (time() - 4838400) . ");";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openidMe,
            $openidOpponent,
            $openidOpponent,
            $openidMe
        ));

        $row = $this->database->fetchRow();
        return $row["MsgCount"];
    }

    /**
     * Selects the last Conversations of an User with OpenId
     */
    public function selectLastConversation($openid) {
        $databaseresult = array();

        $query1 = "SELECT DISTINCT receiver as opponent FROM %s Where `sender` = '%s' order by `timestamp` DESC  LIMIT 0,3;";
        $this->database->query($query1, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openid
        ));

        while ($row = $this->database->fetchRow()) {
            array_push($databaseresult, $row["opponent"]);
        }

        $query2 = "SELECT DISTINCT sender as opponent FROM %s Where `receiver` = '%s' order by `timestamp` DESC  LIMIT 0,3;";
        $this->database->query($query2, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openid
        ));

        while ($row = $this->database->fetchRow()) {
            array_push($databaseresult, $row["opponent"]);
        }

        $databaseresult = array_unique($databaseresult);

        $result = array();
        $managerUser = ImbaManagerUser::getInstance();
        for ($i = 0; $i < 3; $i++) {
            $value = $databaseresult[$i];
            $user = new ImbaUser();
            $user = $managerUser->selectByOpenId($value);
            array_push($result, array("name" => $user->getNickname(), "openid" => $value));
        }

        return json_encode($result);
    }

    /**
     * Selects the timestamp of the last Message in conversation between $openidMe and $openidOpponent
     */
    public function selectLastMessageTimestamp($openidMe, $openidOpponent) {
        $return = 0;
        $query = "SELECT timestamp FROM %s Where (`receiver` = '%s' and sender = `%s`) OR (`receiver` = '%s' and sender = `%s`) ORDER BY timestamp DESC LIMIT 0,1;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MESSAGES, $openidMe, $openidOpponent, $openidOpponent, $openidMe));

        while ($row = $this->database->fetchRow()) {
            $return = $row['timestamp'];
        }
        
        return $return;
    }

    /**
     * Selects all new Messages for a user
     */
    public function selectNewMessagesByOpenid($openid) {
        $query = "SELECT DISTINCT m.sender, p.nickname FROM %s m join %s p on p.openid = m.sender Where `receiver` = '%s' and new = 1";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MESSAGES, ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES, $openid));

        $result = array();
        while ($row = $this->database->fetchRow()) {
            array_push($result, array("openid" => $row["sender"], "name" => $row["nickname"]));
        }

        return json_encode($result);
    }

    /**
     * Mark a message as read
     */
    public function setMessageRead($openidMe, $openidOpponent) {
        $query = "UPDATE %s SET new = 0 where sender = '%s' and receiver = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_USR_MESSAGES,
            $openidOpponent,
            $openidMe
        ));
        //echo sprintf($query, ImbaConstants::$DATABASE_TABLES_USR_MESSAGES, $openidOpponent, $openidMe);
    }

}

?>
