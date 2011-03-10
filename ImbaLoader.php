<?php

require_once 'ImbaConstants.php';
$IMBAdminIndexTemplate = ImbaConstants::$WEB_BASE_TEMPLATE;

switch ($_GET["load"]) {
    case "js":
        session_start();

        /**
         * load jQuery libs
         */
        echo file_get_contents("Libs/jQuery/js/jquery-1.4.4.min.js") . "\n" . "\n";
        echo file_get_contents("Libs/jQuery/js/jquery-ui-1.8.10.custom.min.js") . "\n";
        echo file_get_contents("Libs/DataTables/media/js/jquery.dataTables.min.js") . "\n";

        /**
         * load our js scripts
         */
        echo "var ajaxEntry = '" . ImbaConstants::$WEB_AJAX_ENTRY_FILE . "';\n";
        echo file_get_contents("ImbaLogin.js") . "\n";

        /**
         * Load IMBAdmin index template
         */
        if (file_exists($IMBAdminIndexTemplate)) {
            /**
             * Generate TopNavigation
             */
            session_start();

            require_once 'Model/ImbaUser.php';
            require_once 'ImbaConstants.php';
            require_once 'Controller/ImbaManagerDatabase.php';
            require_once 'Controller/ImbaManagerUser.php';
            require_once 'Controller/ImbaUserContext.php';

            require_once 'Model/ImbaNavigation.php';
            require_once 'Controller/ImbaSharedFunctions.php';

            /**
             * FIXME: please add security to this file!
             * FIXME: load top navigation from database
             */
            include 'Imba.Navigation.php';

            echo "\nhtmlContent = \" \\\n";
            echo "<div id='imbaMenu'><ul class='topnav'>";

            foreach ($topNav->getElements() as $nav) {
                echo "<li><a href='" . $topNav->getElementUrl($nav) . "'>" . $topNav->getElementName($nav) . "</a></li>";
            }

            echo "<li>";
            echo "<a id='imbaMenuImbAdmin' href='#' onclick='javascript: loadImbaAdminModule();'>Auf zum Atem</a>";
            echo "<ul class='subnav'>";

            $contentNav = new ImbaContentNavigation();

            if ($handle = opendir('Ajax/Content/')) {
                $identifiers = array();
                while (false !== ($file = readdir($handle))) {
                    if (strrpos($file, ".Navigation.php") > 0) {
                        include 'Ajax/Content/' . $file;
                        if (count($Navigation->getElements())) {

                            $modIdentifier = str_replace(".Navigation.php", "", $file);
                            echo "<li><a href='#' onclick='javascript: loadImbaAdminModule(\\\"" . $modIdentifier . "\\\");'>" . $Navigation->getName($nav) . "</a></li>";
                            array_push($identifiers, $modIdentifier);
                            $Navigation = null;
                        }
                    }
                }
                closedir($handle);
            }

            echo "</ul>";
            echo "</li>";
            echo "</li></ul></div>";
            echo "\";\ndocument.write(htmlContent);\n\n";


            /**
             * Generate HTML construct (divs)
             */
            echo "\nhtmlContent = \" \\\n";
            $file_array = file($IMBAdminIndexTemplate);
            foreach ($file_array as $line) {
                echo trim($line) . " \\\n";
            }
            echo "\";\ndocument.write(htmlContent);\n\n";
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
