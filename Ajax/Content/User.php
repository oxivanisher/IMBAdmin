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

if ($_POST["action"] != "navigation") {
    echo "<div id='ImbaErrorMsg'>DEBUG:<br /><pre>";
    print_r($_POST);
    echo "</pre></div>";

    switch ($_POST["tabId"]) {
        
        case "#myprofile":
            echo "MYPROFILE";
            break;

        default:
            echo "OVERVIEW";
            break;
    }
}
?>
