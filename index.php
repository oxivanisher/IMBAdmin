<?php
/**
 * Load dependencies
 */
require_once 'Constants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

$databaseController = new ImbaManagerDatabase(ImbaConstants::$DATABASE_HOST, ImbaConstants::$DATABASE_DB, ImbaConstants::$DATABASE_USER, ImbaConstants::$DATABASE_PASS);

?>