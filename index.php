<?php
/**
 * Load dependencies
 */
require_once 'Constants.php';
require_once 'Controller/ManagerDatabase.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

$databaseController = new ManagerDatabase(ImbaConstants::$DATABASE_HOST, ImbaConstants::$DATABASE_DB, ImbaConstants::$DATABASE_USER, ImbaConstants::$DATABASE_PASS);
$user = new ImbaUser();
$user -> loadByOpenId($databaseController, "https://oom.ch/openid/identity/test");

$role = new ImbaUserRole();
$role -> loadById($databaseController, 1);

echo $user -> getOpenId() . "<br>" . $user -> getNickname();
echo "<hr>";
echo $role -> getEqdkp() . "<br>" . $role -> getName();

?>