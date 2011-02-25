<?php
require_once '../Controller/ManagerDatabase.php';

/**
 * Prepare Variables
 */
$managerDatabase = null;
$output = "";

/**
 * Databaseconnection test
 */
try {
	$managerDatabase = new ManagerDatabase("localhost", "alptroeim", "root", "");
	$output .= "Database working.\n";
} catch (Exception $e) {
	$output .= 'Exception abgefangen: ' . $e -> getMessage() . "\n";
}

/**
 * Database Fetch testen
 */
try {
	$managerDatabase -> query("SELECT * FROM `oom_openid_settings` Where name = 'realm'");
	$result = $managerDatabase -> fetchRow();
	if($result["value"] == "Krag'jin") {
		$output .= "Query working.\n";
	}
} catch (Exception $e) {
	$output .= 'Exception abgefangen: ' . $e -> getMessage() . "\n";
}

echo "<pre>ManagerDatabase Test:\n".$output."</pre>"

?>