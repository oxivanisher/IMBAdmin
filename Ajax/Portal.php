<?php

require_once 'ImbaConfig.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerPortal.php';
require_once 'Controller/ImbaManagerNavigation.php';
require_once 'Controller/ImbaUserContext.php';

$managerPortal = ImbaManagerPortal::getInstance();
$managerNavigation = ImbaManagerNavigation::getInstance();

/**
 * ImbaLogin.js asks us to set the users actual selected portal
 */
/**
 * reset current portal to default
 */
if ($_POST['id'] == -1) {
    unset ($_SESSION["IUC_PortalContext"]);
    unset ($_POST['id']);
}

/**
 * Load default portal
 */ if (empty($_POST['id'])) {
    $tmpContext = null;

//ImbaUserContext::setPortalContext(null);
//$_SESSION["IUC_PortalContext"]
    foreach ($managerPortal->selectAll() as $tmpPortal) {
        if (count($tmpPortal->getAliases())) {
            foreach ($tmpPortal->getAliases() as $tmpAlias) {
                $tmpHost = str_replace("http://", "", ImbaSharedFunctions::getDomain($_SERVER['HTTP_REFERER']));
                $tmpHost = str_replace("https://", "", $tmpHost);
                if ($tmpHost == $tmpAlias) {
                    $tmpContext = $tmpPortal->getId();
                }
            }
        }
    }
    if ($tmpContext == null) {
        $tmpContext = ImbaUserContext::getPortalContext();
    }

    $portal = $managerPortal->selectById($tmpContext);
    $navContent = $managerNavigation->renderPortalNavigation($tmpContext);
}
/**
 * set currently portal to $_POST['id']
 */ elseif (!empty($_POST['id'])) {
    ImbaUserContext::setPortalContext($_POST['id']);

    $portal = $managerPortal->selectById(ImbaUserContext::getPortalContext());
    $navContent = $managerNavigation->renderPortalNavigation(ImbaUserContext::getPortalContext());
}
/**
 * get currently active portal and send the data back
 */
echo json_encode(array("name" => $portal->getName(), "icon" => ImbaSharedFunctions::fixWebPath($portal->getIcon()), "navigation" => $navContent));
?>