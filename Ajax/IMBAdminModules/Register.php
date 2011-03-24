<?php

// Extern Session start

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
    echo "You cannot register while logged in.";
} else {
    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();

    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

//    $smarty->assign('role', $role->getName());
//    $smarty->assign('roleIcon', $role->getIcon());
//            $smarty->assign('games', $user->getGames());
//            $smarty->assign('lastLogin', $user->getLastLogin());

    if (ImbaUserContext::getNeedToRegister()) {
        $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());
        $smarty->display('IMBAdminModules/RegisterForm2.tpl');
    } else {
        $smarty->display('IMBAdminModules/RegisterForm1.tpl');
    }
}
?>
