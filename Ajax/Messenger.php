<?php

// Extern Session start
session_start();

require_once 'Model/ImbaMessage.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    $managerMessage = ImbaManagerMessage::getInstance();
    $managerUser = ImbaManagerUser::getInstance();

    /**
     * Recieve Statup Data
     *  - Who was I am talking to
     */
    if (isset($_POST['chatinit'])) {
        echo $managerMessage->selectLastConversation(ImbaUserContext::getOpenIdUrl());
    }

    /**
     * Got something new for user?
     */
    else if (isset($_POST['gotnewmessages'])) {
        echo $managerMessage->selectNewMessagesByOpenid(ImbaUserContext::getOpenIdUrl());
    }

    /**
     * Send a Message
     */
    else if (isset($_POST['message']) && isset($_POST['reciever'])) {
        $message = new ImbaMessage();
        $message->setSender(ImbaUserContext::getOpenIdUrl());
        $message->setReceiver($_POST['reciever']);
        $message->setMessage($_POST['message']);
        $message->setTimestamp(Date("U"));
        $message->setXmpp(0);
        $message->setNew(1);
        $message->setSubject("Was soll hier rein?");

        try {
            $managerMessage->insert($message);
            $managerUser->setMeOnline();

            echo "Message sent";
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }

    /**
     * Set read for a message
     */
    else if (isset($_POST['reciever']) && isset($_POST['setread'])) {
        $managerMessage->setMessageRead(ImbaUserContext::getOpenIdUrl(), $_POST['reciever']);
    }

    /**
     * Recieve Messages
     */
    else if (isset($_POST['loadMessages']) && isset($_POST['reciever'])) {
        try {
            $conversation = $managerMessage->selectConversation(ImbaUserContext::getOpenIdUrl(), $_POST['reciever'], 10);

            $result = array();
            foreach ($conversation as $message) {
                $time = "";
                $sender = "";
                $msg = "";
                if ($message->getSender() == ImbaUserContext::getOpenIdUrl()) {
                    $time = date("d.m.y H:m:s", $message->getTimestamp());
                    $sender = "You";
                    $msg = $message->getMessage();
                } else {
                    $time = date("d.m.y H:m:s", $message->getTimestamp());
                    $sender = "The other";
                    $msg = $message->getMessage();
                }

                array_push($result, array("time" => $time, "sender" => $sender, "message" => $msg));
            }
            echo json_encode($result);
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }
} else {
    echo "Not logged in!";
}
?>