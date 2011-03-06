<?php

//require_once 'ImbaConstants.php';
//echo ImbaConstants::$WEB_AJAX_ENTRY_FILE;

$IMBAdminIndexTemplate = "Templates/ImbaIndex.html.tpl";

switch ($_GET["load"]) {
    case "js":
        echo file_get_contents("Libs/jQuery/js/jquery-1.4.4.min.js") . "\n" . "\n";
        echo file_get_contents("Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js") . "\n";
        echo file_get_contents("Libs/DataTables/media/js/jquery.dataTables.min.js") . "\n";
        echo file_get_contents("ImbaLogin.js") . "\n";

        /**
         * Load IMBAdmin index template
         */
        if (file_exists($IMBAdminIndexTemplate)) {
            echo "htmlContent = \" \\\n";
            $file_array = file($IMBAdminIndexTemplate);
            foreach ($file_array as $line) {
                echo trim($line) . " \\\n";
            }
                        
            echo "\";\n\ndocument.write(htmlContent);\n";
        } else {
            echo 'alert("FATAL ERROR: File not found: ' . $IMBAdminIndexTemplate . '");';
        }

        echo file_get_contents("Controller/ImbaManagerMessage.js") . "\n";
        echo file_get_contents("Controller/ImbaManagerOpenID.js") . "\n";
        break;

    case "css":
        header("location: ImbaLogin.css");
        break;

    default:
        echo "Please specify your request to ImbaLoader.php\n";
        echo "ImbaLoader.php?load=js or ImbaLoader.php?load=css\n";
}
?>
