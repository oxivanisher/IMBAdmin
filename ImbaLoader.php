<?php
require_once 'ImbaConstants.php';
$IMBAdminIndexTemplate = ImbaConstants::$WEB_BASE_TEMPLATE;

switch ($_GET["load"]) {
    case "js":
        print_r($GLOBALS); exit;
        header( 'Content-Type: application/javascript' );
        /**
         * Load IMBAdmin index template
         */
        if (file_exists($IMBAdminIndexTemplate)) {
            session_start();
            $tmpOut = "";

            require_once 'Model/ImbaUser.php';
            require_once 'Controller/ImbaManagerDatabase.php';
            require_once 'Controller/ImbaManagerNavigation.php';
            require_once 'Controller/ImbaManagerUser.php';
            require_once 'Controller/ImbaUserContext.php';

            require_once 'Model/ImbaNavigation.php';
            require_once 'Controller/ImbaSharedFunctions.php';

            /**
             * load static jQuery libs
             */
            $tmpOut .= file_get_contents("Libs/jQuery/js/jquery-1.4.4.min.js") . "\n" . "\n";
            $tmpOut .= file_get_contents("Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js") . "\n";
            $tmpOut .= file_get_contents("Libs/DataTables/media/js/jquery.dataTables.min.js") . "\n";
            $tmpOut .= file_get_contents("Libs/jquery_jeditable/jquery.jeditable.js") . "\n";
            $tmpOut .= file_get_contents("Libs/jgrowl/jquery.jgrowl_compressed.js") . "\n";

            /**
             * load our static js scripts
             */
            $tmpOut .= "var ajaxEntry = '" . ImbaSharedFunctions::fixWebPath(ImbaConstants::$WEB_AJAX_ENTRY_FILE) . "';\n";
            $tmpOut .= file_get_contents("Media/ImbaBaseMethods.js") . "\n";
            $tmpOut .= file_get_contents("Media/ImbaLogin.js") . "\n";
            $tmpOut .= file_get_contents("Media/ImbaAdmin.js") . "\n";
            $tmpOut .= file_get_contents("Media/ImbaGame.js") . "\n";
            $tmpOut .= file_get_contents("Media/ImbaMessaging.js") . "\n";

            /**
             * Begin js/HTML injection code
             */
            $tmpOut .= "htmlContent = \"<div id='imbaAdminContainerWorld'>\\\n";

            /**
             * Render Portal, ImbaAdmin and ImbaGames Navigation
             */
            $managerNavigation = ImbaManagerNavigation::getInstance();
            $tmpOut .= "<div id='imbaMenu'><ul class='topnav'>\\\n";
            $tmpOut .= $managerNavigation->renderPortalNavigation();
            $tmpOut .= $managerNavigation->renderImbaAdminNavigation();
            $tmpOut .= $managerNavigation->renderImbaGameNavigation();
            $tmpOut .= $managerNavigation->renderPortalChooser();
            $tmpOut .= "</ul></div>\\\n";

            /**
             * Render Imba HTML div construct
             */
            $file_array = file($IMBAdminIndexTemplate);
            $thrustRoot = ImbaSharedFunctions::getTrustRoot();
            foreach ($file_array as $line) {
                $tmpOut .= str_replace("MYWEBPATHREPLACE", $thrustRoot, trim($line)) . "\\\n";
            }

            /**
             * End js/HTML injection code
             */
            $tmpOut .= "</div>\";\ndocument.write(htmlContent);\n\n";

            /**
             * Write all informations at once (should be much faster than echo
             */
            echo $tmpOut;
        } else {
            echo 'alert("FATAL ERROR! File ' . $IMBAdminIndexTemplate . ' not found. Aborting...");';
        }
        break;

    case "css":
        $tmpOut = "";
        $tmpOut .= file_get_contents("Media/ImbaLogin.css");
        $tmpOut .= file_get_contents("Media/ImbaAdmin.css");
        echo $tmpOut;
        break;

    default:
        echo "Please specify your request to ImbaLoader.php\n";
        echo "ImbaLoader.php?load=js or ImbaLoader.php?load=css\n";
}
?>
