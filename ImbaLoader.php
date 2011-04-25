<?php
header('Access-Control-Allow-Origin: *');

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaSharedFunctions.php';
$smarty = ImbaSharedFunctions::newSmarty();
$smarty->assign("thrustRoot", ImbaSharedFunctions::getTrustRoot());

switch ($_GET["load"]) {
    case "js":
        header('Content-Type: application/javascript');

        session_start();
        require_once 'Model/ImbaUser.php';
        require_once 'Model/ImbaNavigation.php';
        require_once 'Controller/ImbaManagerDatabase.php';
        require_once 'Controller/ImbaManagerNavigation.php';
        require_once 'Controller/ImbaManagerUser.php';
        require_once 'Controller/ImbaUserContext.php';

        $managerNavigation = ImbaManagerNavigation::getInstance();

        if (($_SERVER['HTTP_REFERER'] == ImbaSharedFunctions::getTrustRoot()) && (ImbaConstants::$WEB_FORCE_PROXY == false)) {
            $smarty->assign("authPath", ImbaConstants::$WEB_AUTH_MAIN_PATH);
            $smarty->assign("ajaxPath", ImbaSharedFunctions::fixWebPath(ImbaConstants::$WEB_AJAX_MAIN_PATH));
        } else {
            $smarty->assign("authPath", ImbaConstants::$WEB_AUTH_PROXY_PATH);
            $smarty->assign("ajaxPath", ImbaSharedFunctions::fixWebPath(ImbaConstants::$WEB_AJAX_PROXY_PATH));
        }

        $smarty->assign("phpSessionID", session_id());
        $smarty->assign("PortalNavigation", $managerNavigation->renderPortalNavigation());
        $smarty->assign("ImbaAdminNavigation", $managerNavigation->renderImbaAdminNavigation());
        $smarty->assign("ImbaGameNavigation", $managerNavigation->renderImbaGameNavigation());
        $smarty->assign("PortalChooser", $managerNavigation->renderPortalChooser());

        $smarty->display('ImbaLoaderJs.tpl');
        break;

    case "css":
        header('Content-type: text/css');
        $smarty->display('ImbaLoaderCss.tpl');
        break;

    default:
        header('Content-type: text/html');
        $smarty->display('ImbaLoader.tpl');
}
?>