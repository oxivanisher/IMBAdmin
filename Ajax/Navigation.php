<?php

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();

$moduleFile = "Ajax/Content/" . $_POST["module"] . "Navigation.php";

if (file_exists($moduleFile)) {
    /**
     * load the module file
     */
    require_once $moduleFile;

    switch ($_POST["request"]) {
        case "nav":
            foreach ($Navigation->getElements() as $NavigationEntry) {
                array_push($nav, array("id" => $NavigationEntry, "name" => $Navigation->getElementName($NavigationEntry)));
            }
            break;
        case "name":
                $myName = (string) $Navigation->getName();
                array_push($nav, array("name" => $myName));
            break;
    }
} else {
    array_push($nav, array("id" => "error", "name" => "Module not found (" . $moduleFile . ")!"));
}
echo json_encode($nav);
?>
