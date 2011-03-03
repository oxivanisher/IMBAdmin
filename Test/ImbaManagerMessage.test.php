<?php

chdir("../");
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerDatabase.php';

/**
 * Prepare Variables
 */
$managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
$managerMessage = new ImbaManagerMessage($managerDatabase);
$output = "";

/**
 * Chat init
 */
$chatinit = $managerMessage->seletLastConversation("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
$output .= "Chatinit output: " . $chatinit . "\n";

/**
 * Got new Messages for User?
 */
$gotnewmsg = $managerMessage->selectNewMessagesByOpenid("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
$output .= "Got new Messages output: " . $gotnewmsg . "\n";

echo "<pre>ImbaManagerMessage Test:\n" . $output . "</pre>";
?>