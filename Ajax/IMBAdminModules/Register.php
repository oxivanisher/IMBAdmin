<?php

// Extern Session start

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
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
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);

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
