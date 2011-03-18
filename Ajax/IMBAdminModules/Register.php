<?php

// Extern Session start

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    echo "You cannot register while logged in.";
} else {
    /**
     * Load the database
     */
    $managerUser = new ImbaManagerUser();

    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

//    $smarty->assign('role', $role->getName());
//    $smarty->assign('roleIcon', $role->getIcon());
//            $smarty->assign('games', $user->getGames());
//            $smarty->assign('lastLogin', $user->getLastLogin());


    $smarty->display('ImbaAjaxRegister.tpl');
}
?>
