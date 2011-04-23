<?php

// Extern Session start
session_start();

require_once 'ImbaConfig.php';
require_once 'Controller/ImbaManagerPortal.php';
require_once 'Controller/ImbaUserContext.php';

$managerPortal = ImbaManagerPortal::getInstance();

/**
 * ImbaLogin.js asks us to set the users actual selected portal
 */
/**
 * reset current portal to default
 */ if ($_POST['id'] == -1) {
    ImbaUserContext::setPortalContext("");
}
/**
 * set currently portal to $_POST['id']
 */ elseif (!empty($_POST['id'])) {
    ImbaUserContext::setPortalContext($_POST['id']);
}
/**
 * get currently active portal and send the data back
 */
$portal = $managerPortal->selectById(ImbaUserContext::getPortalContext());

echo json_encode(array($portal->getName() => $portal->getIcon()));
?>