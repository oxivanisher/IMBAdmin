<?php

require_once 'Model/ImbaNavigation.php';


/**
 * Show this navigation only if we are logged in
 */
if (ImbaUserContext::getLoggedIn()) {

    /**
     * Define Navigation
     */
    $Navigation = new ImbaContentNavigation();

    /**
     * Set module name
     */
    $Navigation->setName("Administration");

    /**
     * Set tabs
     */
    $Navigation->addElement("baem", "B&auml;m!");
    $Navigation->addElement("hacktheplanet", "Take over the world");
}
?>
