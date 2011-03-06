<?php

//require_once 'ImbaConstants.php';
//echo ImbaConstants::$WEB_AJAX_ENTRY_FILE;

switch ($_GET["load"]) {
    case "js":
        echo file_get_contents("Libs/jQuery/js/jquery-1.4.4.min.js") . "\n";
        echo file_get_contents("Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js") . "\n";
        /*
         * mimimi
          echo str_replace("AJAX_ENTRY_REPLACE",
          ImbaConstants::$WEB_AJAX_ENTRY_FILE,
          file_get_contents("ImbaLogin.js") . "\n"); */
        echo file_get_contents("ImbaLogin.js") . "\n";
        echo file_get_contents("Controller/ImbaManagerMessage.js") . "\n";
        echo file_get_contents("Controller/ImbaManagerOpenID.js") . "\n";
        break;

    case "css":
        echo file_get_contents("ImbaLogin.css") . "\n";
        break;
    
    default:
        echo "Please specify your request to ImbaLoader.php\n";
        echo "ImbaLoader.php?load=js or ImbaLoader.php?load=css\n";
}

?>
