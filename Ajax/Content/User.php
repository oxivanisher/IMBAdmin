<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaContentNavigation.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';

echo "test";
$Navigation = new ImbaContentNavigation();
$Navigation->addElement("overview", "Benutzer &Uuml;bersicht");
$Navigation->addElement("myprofile", "Mein Profil Editieren");


if ($_POST["tabId"] == "#tabId1") {
    echo "content 1";
} else if ($_POST["tabId"] == "#tabId2") {
    echo "content 2";
} else if ($_POST["tabId"] == "#tabId3") {
    echo "content 3";
} else if ($_POST["tabId"] == "#tabId4") {
    echo "content 4";
}
?>
