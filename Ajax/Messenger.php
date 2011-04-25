<?php

require_once 'Model/ImbaMessage.php';
require_once 'Model/ImbaChatMessage.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerChatChannel.php';
require_once 'Controller/ImbaManagerChatMessage.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    $managerMessage = ImbaManagerMessage::getInstance();
    $managerChatChannel = ImbaManagerChatChannel::getInstance();
    $managerChatMessage = ImbaManagerChatMessage::getInstance();
    $managerUser = ImbaManagerUser::getInstance();

    /**
     * Got something new for user?
     */ if (isset($_POST['gotnewmessages'])) {
        echo json_encode($managerMessage->selectMyNewMessages());
    }

    /**
     * Send a Message
     */ else if (isset($_POST['message']) && isset($_POST['reciever'])) {
        $message = new ImbaMessage();
        $message->setSender($managerUser->selectById(ImbaUserContext::getUserId()));
        $message->setReceiver($managerUser->selectById($_POST['reciever']));
        $message->setMessage($_POST['message']);
        $message->setTimestamp(date("U"));
        $message->setXmpp(0);
        $message->setNew(1);
        $message->setSubject("AJAX GUI");

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
     */ else if (isset($_POST['reciever']) && isset($_POST['setread'])) {
        $managerMessage->setMessageRead($_POST['reciever']);
    }

    /**
     * Recieve Messages
     */ else if (isset($_POST['loadMessages']) && isset($_POST['reciever'])) {
        try {
            $conversation = $managerMessage->selectAllByOpponentId($_POST['reciever']);

            $result = array();
            foreach ($conversation as $message) {
                $time = date("d.m.y H:i:s", $message->getTimestamp());
                $sender = $message->getSender()->getNickname();
                $msg = $message->getMessage();

                array_push($result, array("time" => $time, "sender" => $sender, "message" => $msg));
            }
            $result = array_reverse($result);
            echo json_encode($result);
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }
    /**
     * Load the Channels
     */ else if (isset($_POST['loadchannels'])) {
        $result = array();
        foreach ($managerChatChannel->selectAll() as $channel) {
            array_push($result, array("user" => false, "channel" => $channel->getName(), "channelId" => $channel->getId()));
        }
        echo json_encode($result);
    }
    /**
     * Load the ChatMessages
     */ else if (isset($_POST['loadchat']) && isset($_POST['since']) && isset($_POST['channelid'])) {
        $result = array();
        $channel = $managerChatChannel->selectById($_POST['channelid']);
        foreach ($managerChatMessage->selectAllByChannel($channel, $_POST['since']) as $message) {
            array_push($result, array(
                "id" => $message->getId(),
                "time" => date("d.m.y H:i:s", $message->getTimestamp()),
                "nickname" => $message->getSender()->getNickname(),
                "message" => $message->getMessage()
            ));
        }
        $result = array_reverse($result);
        echo json_encode($result);
    }
    /**
     * init the ChatMessages
     */ else if (isset($_POST['initchat']) && isset($_POST['channelid'])) {
        $result = array();
        $channel = $managerChatChannel->selectById($_POST['channelid']);
        foreach ($managerChatMessage->selectAllByChannel($channel, -1) as $message) {
            array_push($result, array(
                "id" => $message->getId(),
                "time" => date("d.m.y H:i:s", $message->getTimestamp()),
                "nickname" => $message->getSender()->getNickname(),
                "message" => $message->getMessage()
            ));
        }
        $result = array_reverse($result);
        echo json_encode($result);
    }
    /**
     * Send a ChatMessages
     */ else if (isset($_POST['message']) && isset($_POST['channelid'])) {
        if (trim($_POST['message']) != "") {
            $channel = $managerChatChannel->selectById($_POST['channelid']);
            $message = new ImbaChatMessage();
            $message->setChannel($channel);
            $message->setMessage($_POST['message']);

            $managerChatMessage->insert($message);
            echo "Message sent";
        } else {
            echo "No Message";
        }
    }
} else {
    echo "Not logged in!";
}
?>