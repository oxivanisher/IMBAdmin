<?php

require_once 'ImbaConstants.php';

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();

$moduleFile = "Ajax/Content/" . $_POST["module"] . ".Navigation.php";

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
            echo json_encode($nav);
            break;
        case "name":
            $myName = (string) $Navigation->getName();
            echo $myName;
            break;
        default:
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_IN_MODULE;
            break;
    }
} else {
    switch ($_POST["request"]) {
        case "nav":
            array_push($nav, array("id" => "error", "name" => "Module not found (" . $moduleFile . ")!"));
            echo json_encode($nav);
            break;
        case "name":
            echo "Module not found (" . $moduleFile . ")!";
            break;
        default:
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_OUT_MODULE;
            break;
    }
}
?>
