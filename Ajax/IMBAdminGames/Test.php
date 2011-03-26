<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * create a new smarty object
     */
    //$smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();


    switch ($_POST["request"]) {

        case "settings":
            echo "settings";
            break;

        default:
            echo "overview";
    }
    //$smarty->assign('test', true);
    //$smarty->display('IMBAdminGames/WelcomeIndex.tpl');
} else {
    echo "Not logged in";
}
?>