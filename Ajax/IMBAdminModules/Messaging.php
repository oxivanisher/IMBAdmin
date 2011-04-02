<?php

// Extern Session start

session_start();

//require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
//require_once 'Controller/ImbaManagerUser.php';
//require_once 'Controller/ImbaManagerUserRole.php';
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
    $roleManager = ImbaManagerUserRole::getInstance();

    switch ($_POST["request"]) {
        case "setchannels":

            break;

        case "channels":

            $smarty->display('IMBAdminModules/MessagingChannels.tpl');
            break;

        /**
         * History
         */
        case "viewchathistory":

            $smarty->display('IMBAdminModules/MessagingChatHistory.tpl');
            break;

        case "viewmessagehistory":

            $smarty->display('IMBAdminModules/MessagingMessageHistory.tpl');
            break;

        default:
        //case historyoverview

            $smarty->display('IMBAdminModules/MessagingHistoryOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
