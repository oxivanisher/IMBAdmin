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
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);

    switch ($_POST["request"]) {

        case "myprofile":
            $user = $managerUser->selectByOpenId(ImbaUserContext::getOpenIdUrl());

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('website', $user->getWebsite());
            $smarty->assign('motto', $user->getMotto());
            $smarty->assign('avatar', $user->getAvatar());
            $smarty->assign('signature', $user->getSignature());
            $smarty->assign('lastonline', ImbaSharedFunctions::getNiceAge($user->getLastonline()));

            if ($user->getSex() == "M") {
                $smarty->assign('sex', '');
            } else {
                
            }
            
            $roleManager = new ImbaManagerUserRole($managerDatabase);
            $role = $roleManager->selectById($user->getRole());

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());


            $smarty->display('ImbaWebUserMyprofile.tpl');
            break;

        case "viewprofile":
            if (isset($_POST["payLoad"]))
                $_POST["openid"] = $_POST["payLoad"];

            $user = $managerUser->selectByOpenId($_POST["openid"]);

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('website', $user->getWebsite());
            $smarty->assign('motto', $user->getMotto());
            $smarty->assign('avatar', $user->getAvatar());
            $smarty->assign('signature', $user->getSignature());
            $smarty->assign('lastonline', ImbaSharedFunctions::getNiceAge($user->getLastonline()));

            $roleManager = new ImbaManagerUserRole($managerDatabase);
            $role = $roleManager->selectById($user->getRole());

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());
//            $smarty->assign('games', $user->getGames());
//            $smarty->assign('lastLogin', $user->getLastLogin());


            $smarty->display('ImbaWebUserViewprofile.tpl');
            break;

        default:
            $smarty->assign('link', ImbaSharedFunctions::genAjaxWebLink($_POST["module"], "viewprofile", $_POST["User"]));
            $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());
/*
            class My_Security_Policy extends Smarty_Security {
                public $if_functs = true;
                // disable all PHP functions
                public $php_functions = true;
                // remove PHP tags
                public $php_handling = Smarty::PHP_REMOVE;
                // allow everthing as modifier
                public $modifiers = array();
            }

            $smarty->enableSecurity('My_Security_Policy');
*/

            $smarty_users = array();
            foreach ($users as $user) {
                array_push($smarty_users, array(
                    'nickname' => $user->getNickname(),
                    'openid' => $user->getOpenID(),
                    'lastonline' => ImbaSharedFunctions::getNiceAge($user->getLastonline()),
                    'jabber' => "",
                    'games' => "W E R"
                ));
            }
            $smarty->assign('susers', $smarty_users);

            $smarty->display('ImbaWebUserOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
