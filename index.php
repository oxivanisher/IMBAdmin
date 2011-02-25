<?php
    /**
	 * Load dependencies
	 */
    require_once 'Constants.php';
    require_once 'Controller/ManagerDatabase.php';
    require_once 'Model/ImbaUser.php';
	
	$databaseController = new ManagerDatabase(ImbaConstants::$DATABASE_HOST, ImbaConstants::$DATABASE_DB, ImbaConstants::$DATABASE_USER, ImbaConstants::$DATABASE_PASS);
	$user = new ImbaUser();
	$user->loadByOpenId($databaseController, "https://oom.ch/openid/identity/test");
	
	echo $user->getOpenId();
	echo "<br>";
	echo $user->getNickname();
?>