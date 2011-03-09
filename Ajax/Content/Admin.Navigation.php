<?php

require_once 'Model/ImbaNavigation.php';

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

?>
