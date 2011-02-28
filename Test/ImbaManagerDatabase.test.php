<?php

chdir ("../");
require_once 'Controller/ImbaManagerDatabase.php';

/**
 * Prepare Variables
 */
$managerDatabase = null;
$output = "";

/**
 * Databaseconnection test
 */
try {
    $managerDatabase = new ImbaManagerDatabase("localhost", "imbadmin", "imbadmin", "ua0Quee2");
    $output .= "Database working.\n";
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

/**
 * Database Fetch testen
 */
try {
    $managerDatabase->query("SELECT * FROM `oom_openid_settings` Where name = '%s'", array("realm"));    
    $result = $managerDatabase->fetchRow();
    if ($result["value"] == "Krag'jin") {
        $output .= "Query working.\n";
    }
} catch (Exception $e) {
    $output .= 'Exception abgefangen: ' . $e->getMessage() . "\n";
}

echo "<pre>ImbaManagerDatabase Test:\n" . $output . "</pre>"
?>