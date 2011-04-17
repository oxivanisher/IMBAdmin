<?php

// Extern Session start

session_start();

//require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
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

            array_push($smartyMessages, array(
                "openid" => "",
                "nickname" => "",
                "timestamp" => "",
                "message" => ""
            ));

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

            $smartyConversations = array();

            array_push($smartyConversations, array(
                "openid" => "",
                "nickname" => "",
                "lastmessage" => "",
                "nummessages" => ""
            ));

            $smarty->assign("users", $smartyConversations);
            $smarty->display('IMBAdminModules/MessagingMessageOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
