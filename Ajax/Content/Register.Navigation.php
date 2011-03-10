<?php

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
