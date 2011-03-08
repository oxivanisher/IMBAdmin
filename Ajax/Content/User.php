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

    /**
     * Load the database
     */
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);

    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    switch ($_POST["tabId"]) {

        case "myprofile":
            $smarty->assign('name', 'Ned');


            $smarty->display('ImbaWebUserMyprofile.tpl');
            break;

        case "viewprofile":
            $user = $managerUser->selectByOpenId($_POST["openid"]);

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', $user->getLastname());
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('website', $user->getWebsite());
            
            $roleManager = new ImbaManagerUserRole($managerDatabase);
            $role = $roleManager->selectById($user->getRole());
            
            
            $smarty->assign('role', $role->getName());
//            $smarty->assign('games', $user->getGames());
//            $smarty->assign('lastLogin', $user->getLastLogin());
            
           
            $smarty->display('ImbaWebUserViewprofile.tpl');
            break;

        default:
            $smarty->assign('link', ImbaSharedFunctions::genAjaxWebLink($_POST["module"], "viewprofile", $_POST["User"]));

            $users = $managerUser->selectAllUser(ImbaUserContext::getOpenIdUrl());

            $smarty_users = array();
            foreach ($users as $user) {
                array_push($smarty_users, array('nickname' => $user->getNickname(), 'openid' => $user->getOpenID()));
            }
            $smarty->assign('susers', $smarty_users);

            echo "<div id='ImbaContentContainer'>";
            $smarty->display('ImbaWebUserOverview.tpl');
            echo "</div>";
            break;
    }
} else {
    echo "Not logged in";
}
?>
