<?php

require_once 'ImbaConfig.php';
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
 */ if (($_POST['id'] == -1) || (empty($_POST['id']))) {
    $tmpContext = null;

    ImbaUserContext::setPortalContext(null);

    foreach ($managerPortal->selectAll() as $tmpPortal) {
        if (count($tmpPortal->getAliases())) {
            foreach ($tmpPortal->getAliases() as $tmpAlias) {
                if ($_SERVER[HTTP_HOST] == $tmpAlias) {
                    $tmpContext = $tmpPortal->getId();
                }
            }
        }
    }
    if ($tmpContext == null) {
        $tmpContext = ImbaConstants::$SETTINGS['DEFAULT_PORTAL_ID'];
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
echo json_encode(array("name" => $portal->getName(), "icon" => $portal->getIcon(), "navigation" => $navContent));
?>