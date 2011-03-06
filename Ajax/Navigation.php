<?php

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();

if (file_exists("Ajax/Content/" . $_POST["module"] . ".php")) {
    require_once "Ajax/Content/" . $_POST["module"] . ".php";

    foreach ($Navigation->getElements() as $NavigationEntry) {
        array_push($nav, array("id" => $NavigationEntry, "name" => $Navigation->getElementName($NavigationEntry)));
    }
} else {
    array_push($nav, array("id" => "error", "name" => "Module not found!"));
}
echo json_encode($nav);
?>
