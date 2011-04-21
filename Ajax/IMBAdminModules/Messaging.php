<?php

// Extern Session start

session_start();

//require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerChatChannel.php';
require_once 'Controller/ImbaManagerChatMessage.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();
    $managerRole = ImbaManagerUserRole::getInstance();
    $managerMessage = ImbaManagerMessage::getInstance();
    $managerChatChannel = ImbaManagerChatChannel::getInstance();
    $managerChatMessage = ImbaManagerChatMessage::getInstance();

    switch ($_POST["request"]) {
        /**
         * History
         */
        case "viewchathistory":

            $smartyMessages = array();

            array_push($smartyMessages, array(
                "openid" => "",
                "nickname" => "",
                "timestamp" => "",
                "message" => ""
            ));

            $smarty->assign("messages", $smartyMessages);

            $smarty->display('IMBAdminModules/MessagingChatHistory.tpl');
            break;

        case "viewmessagehistory":
            $smartyMessages = array();
            foreach ($managerMessage->selectConversation(ImbaUserContext::getOpenIdUrl(), $_POST['openid'], 0) as $myMessage) {
                $myTimestamp = $myMessage->getTimestamp();
                $myTimestring = ImbaSharedFunctions::getNiceAge($myTimestamp);
                array_push($smartyMessages, array(
                    "openid" => $myMessage->getSender(),
                    "nickname" => $managerUser->selectByOpenId($myMessage->getSender())->getNickname(),
                    "timestamp" => $myTimestamp,
                    "timestring" => $myTimestring,
                    "message" => $myMessage->getMessage()
                ));
            }

            $smarty->assign("messages", $smartyMessages);
            $smarty->display('IMBAdminModules/MessagingMessageHistory.tpl');
            break;

        case "viewchatoverview":
            $smartyChatschannels = array();

            foreach ($managerChatChannel->selectAll() as $tmpChannel) {
                array_push($smartyChatschannels, array(
                    "id" => $tmpChannel->getId(),
                    "name" => $tmpChannel->getName(),
                    "lastmessage" => "Fixme",
                    "nummessages" => "Fixme"
                ));
            }

            $smarty->assign("channels", $smartyChatschannels);
            $smarty->display('IMBAdminModules/MessagingChatOverview.tpl');
            break;

        default:
            //case viewmessageoverview
            $newList = array();
            foreach ($managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl()) as $user) {
                $timestamp = $managerMessage->selectLastMessageTimestamp($user->getOpenId());
                array_push($newList, array("timestamp" => $timestamp, "id" => $user->getId()));
            }
            //ksort($newList);

            $smartyConversations = array();
            foreach ($newList as $item) {
                $tmpUser = $managerUser->selectById($item['id']);
                $tmpNickname = $tmpUser->getNickname();
                array_push($smartyConversations, array(
                    "id" => $item['id'],
                    "nickname" => $tmpNickname,
                    "lastmessagets" => $item['timestamp'],
                    "lastmessagestr" => ImbaSharedFunctions::getNiceAge($item['timestamp']),
                    "nummessages" => $managerMessage->selectMessagesCount($item['id'])
                ));
            }

            $smarty->assign("users", $smartyConversations);
            $smarty->display('IMBAdminModules/MessagingMessageOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
