<?php

chdir ("../");
require_once 'Controller/ImbaManagerDatabase.php';

        
/**
 * Prepare Variables
 */
$managerDatabase = null;
$output = "";

if (isset($_GET["setquery"])) {    
    $managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
    $managerDatabase->test = $_GET["setquery"];
}

if (isset($_GET["getquery"])){
    $managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
    echo $managerDatabase->test;    
}

/**
 * Databaseconnection test
 */
try {
    $managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
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