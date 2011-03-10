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
 * Set this navigation only, when we are logged out
 */
if (!ImbaUserContext::getLoggedIn()) {

    /**
     * Set module name
     */
    $Navigation->setName("Registrieren");

    /**
     * Set tabs
     */
    $Navigation->addElement("reg", "Registration");
}
?>
