<?php

require_once 'ImbaConstants.php';
$IMBAdminIndexTemplate = ImbaConstants::$WEB_BASE_TEMPLATE;

switch ($_GET["load"]) {
    case "js":
        header('Content-Type: application/javascript');
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
             * depending of proxy or not and set the js var
             */
            if ($_SERVER['HTTP_REFERER'] == ImbaSharedFunctions::getTrustRoot()) {
                $authPath = ImbaConstants::$WEB_AUTH_MAIN_PATH;
                $ajaxPath = ImbaConstants::$WEB_AJAX_MAIN_FILE;
            } else {
                $authPath = ImbaConstants::$WEB_AUTH_PROXY_PATH;
                $ajaxPath = ImbaConstants::$WEB_AJAX_PROXY_FILE;
            }
            $tmpOut .= "var ajaxEntry = '" . ImbaSharedFunctions::fixWebPath($ajaxPath) . "';\n";

            if (empty($_SESSION['IUC_jsCache'])) {
                $jsFiles = array(
                    /**
                     * These are all our needed js files
                     */
                    "Libs/jQuery/js/jquery-1.4.4.min.js",
                    "Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js",
                    "Libs/DataTables/media/js/jquery.dataTables.min.js",
                    "Libs/jquery_jeditable/jquery.jeditable.js",
                    "Libs/jgrowl/jquery.jgrowl_compressed.js",
                    "Media/ImbaBaseMethods.js",
                    "Media/ImbaLogin.js",
                    "Media/ImbaAdmin.js",
                    "Media/ImbaGame.js",
                    "Media/ImbaMessaging.js"
                );
                $_SESSION['IUC_jsCache'] = "";
                foreach ($jsFiles as $jsFile) {
                    $_SESSION['IUC_jsCache'] .= file_get_contents($jsFile) . "\n" . "\n";
                }
            }
            $tmpOut .= $_SESSION['IUC_jsCache'];



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
                $tmpOut .= trim($line) . "\\\n";
            }

            /**
             * End js/HTML injection code
             */
            $tmpOut .= "</div>\";\ndocument.write(htmlContent);\n\n";

            /**
             * Some replace magic
             */
            $tmpOut = str_replace("MYWEBPATHREPLACE", $thrustRoot, $tmpOut);
            $tmpOut = str_replace("MYAUTHPATHREPLACE", $authPath, $tmpOut);

            /**
             * Write the stuff
             */
            echo $tmpOut;
        } else {
            echo 'alert("FATAL ERROR! File ' . $IMBAdminIndexTemplate . ' not found. Aborting...");';
        }
        break;

    case "css":
        if (empty($_SESSION['IUC_cssCache'])) {

            $_SESSION['IUC_cssCache'] .= file_get_contents("Media/ImbaLogin.css");
            $_SESSION['IUC_cssCache'] .= file_get_contents("Media/ImbaAdmin.css");
        }
        echo $_SESSION['IUC_cssCache'];
        break;

    default:
        echo "Please specify your request to ImbaLoader.php\n";
        echo "ImbaLoader.php?load=js or ImbaLoader.php?load=css\n";
}
?>
