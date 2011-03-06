<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaContentNavigation.php';
require_once 'ImbaConstants.php';
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

        echo "<div id='ImbaErrorMsg'>DEBUG:<br /><pre>";
        print_r($_POST);
        echo "</pre></div>";

        switch ($_POST["tabId"]) {

            case "#myprofile":
                echo "MYPROFILE";
                break;

            default:
                $users = $managerUser->selectAllUser(ImbaUserContext::getOpenIdUrl());
                $result = array();
                foreach ($users as $user) {
                    echo $user->getNickname() . " ";
                }

                echo json_encode($result);

                break;
        }
    }
} else {
    echo "Not logged in";
}
?>
