<?php

// Extern Session start

require_once 'ImbaConstants.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaNavigation.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';

$Navigation = new ImbaContentNavigation();
$Navigation->addElement("overview", "Benutzer &Uuml;bersicht");
$Navigation->addElement("myprofile", "Mein Profil Editieren");
/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * generate no content if only navigation is needed
     */
    if ($_POST["action"] != "navigation") {
        /**
         * Load the database
         */
        $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
        $managerUser = new ImbaManagerUser($managerDatabase);

        /**
         *  put full path to Smarty.class.php
         */
        require('Libs/smarty/libs/Smarty.class.php');
        $smarty = new Smarty();

        /**
         * Set smarty dirs
         */
        $smarty->setTemplateDir('Templates');
        $smarty->setCompileDir('Libs/smarty/templates_c');
        $smarty->setCacheDir('Libs/smarty/cache');
        $smarty->setConfigDir('Libs/smarty/configs');

        /* Debug output
          echo "<div id='ImbaErrorMsg' style=''>DEBUG:<br /><pre>";
          print_r($_POST);
          echo "</pre></div>";
         */
        switch ($_POST["tabId"]) {

            case "#myprofile":
                $smarty->assign('name', 'Ned');


                $smarty->display('ImbaWebUserMyprofile.tpl');
                break;

            case "#viewprofile":
                $smarty->assign('name', 'Ned');


                $smarty->display('ImbaWebUserViewprofile.tpl');
                break;

            default:
                $smarty->assign('link', ImbaSharedFunctions::genAjaxWebLink($_POST["mod_user"], "viewprofile", $_POST["User"]));

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
    }
}
?>
