<?php

chdir("../");
require_once 'Controller/ImbaManagerGame.php';
require_once 'Controller/ImbaManagerGameCategory.php';
require_once 'Controller/ImbaManagerGameProperty.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Model/ImbaGame.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaGameCategory.php';
require_once 'Model/ImbaGameProperty.php';
require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerChatChannel.php';
require_once 'Model/ImbaChatChannel.php';


$managerChatChannel = ImbaManagerChatChannel::getInstance();
var_dump($managerChatChannel->channelUsers(2));
?>
