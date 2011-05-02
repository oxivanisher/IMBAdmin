<?php

chdir("../");

function includeitall() {
    require_once 'Controller/ImbaManagerBase.php';
    require_once 'Controller/ImbaManagerChatChannel.php';
    require_once 'Controller/ImbaManagerChatMessage.php';
    require_once 'Controller/ImbaManagerDatabase.php';
    require_once 'Controller/ImbaManagerGameCategory.php';
    require_once 'Controller/ImbaManagerGame.php';
    require_once 'Controller/ImbaManagerGameProperty.php';
    require_once 'Controller/ImbaManagerLog.php';
    require_once 'Controller/ImbaManagerMessage.php';
    require_once 'Controller/ImbaManagerMultigaming.php';
    require_once 'Controller/ImbaManagerNavigation.php';
    require_once 'Controller/ImbaManagerOauth.php';
    require_once 'Controller/ImbaManagerOpenID.php';
    require_once 'Controller/ImbaManagerPortalEntry.php';
    require_once 'Controller/ImbaManagerPortal.php';
    require_once 'Controller/ImbaManagerUser.php';
    require_once 'Controller/ImbaManagerUserRole.php';
    require_once 'Controller/ImbaSharedFunctions.php';
    require_once 'Controller/ImbaUserContext.php';
    require_once 'ImbaConfig.php';
    require_once 'ImbaConstants.php';
    require_once 'Libs/reCaptcha/recaptchalib.php';
    require_once 'Model/ImbaBase.php';
    require_once 'Model/ImbaChatChannel.php';
    require_once 'Model/ImbaChatMessage.php';
    require_once 'Model/ImbaGameCategory.php';
    require_once 'Model/ImbaGame.php';
    require_once 'Model/ImbaGameProperty.php';
    require_once 'Model/ImbaGamePropertyValue.php';
    require_once 'Model/ImbaLog.php';
    require_once 'Model/ImbaMessage.php';
    require_once 'Model/ImbaNavigation.php';
    require_once 'Model/ImbaPortalEntry.php';
    require_once 'Model/ImbaPortal.php';
    require_once 'Model/ImbaUser.php';
    require_once 'Model/ImbaUserRole.php';
}

includeitall();

$manager = ImbaManagerPortal::getInstance();
echo "<pre>";

$portal = $manager->selectById("3");
var_dump($portal);



echo "</pre>";
?>
