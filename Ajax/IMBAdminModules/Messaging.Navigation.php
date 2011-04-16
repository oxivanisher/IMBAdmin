<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaUserContext.php';

require_once 'Model/ImbaNavigation.php';


/**
 * Define Navigation
 */
$Navigation = new ImbaContentNavigation();

/**
 * Set module name
 */
$Navigation->setName("Nachrichten");
$Navigation->setComment("Hier kannst du deine Chateinstellungen anpassen und vergangene Gespr&auml;che ansehen.");

/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(true);
$Navigation->setShowLoggedOff(false);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(1);

/**
 * Set tabs
 */
$Navigation->addElement("historyoverview", "Nachrichtenverlauf ansehen", "Hier kannst vergangene Konversationen ansehen.");
//$Navigation->addElement("channels", "Chat Kan&auml;le", "Hier kannst du deine Chat Kan&auml;le einstellen.");
?>
