<?php

// Extern Session start

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
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

            foreach ($user as $key => $value) {
                $smarty->assign($key, $value);
            }
            print_r($_POST);
            $smarty->assign("test", "asdasd");
            var_dump($Smarty->_tpl_vars);

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
