<?php

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();

$moduleFile = "Ajax/Content/" . $_POST["module"] . ".php";

if (file_exists($moduleFile)) {
    require_once $moduleFile;

    foreach ($Navigation->getElements() as $NavigationEntry) {
        array_push($nav, array("id" => $NavigationEntry, "name" => $Navigation->getElementName($NavigationEntry)));
    }
} else {
    array_push($nav, array("id" => "error", "name" => "Module not found (" . $moduleFile . ")!"));
}
echo json_encode($nav);
?>
