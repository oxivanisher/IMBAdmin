<?php

chdir("../");
require_once 'Controller/ImbaManagerMessage.php';

/**
 * Prepare Variables
 */
$managerMessage = new ImbaManagerMessage();
$output = "";

/**
 * Chat init
 */
$chatinit = $managerMessage->selectLastConversation("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
$output .= "Chatinit output: " . $chatinit . "\n";

/**
 * Got new Messages for User?
 */
$gotnewmsg = $managerMessage->selectNewMessagesByOpenid("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
$output .= "Got new Messages output: " . $gotnewmsg . "\n";

/**
 * conversation
 */
$conversation = $managerMessage->selectConversation("http://openid-provider.appspot.com/Steffen.So@googlemail.com", "https://oom.ch/openid/identity/oxi");
$timestamp = $conversation[count($conversation) - 1]->getTimestamp();
$output .= "Last selectConversation output: " . date("d.m.Y", $timestamp) . "\n";



echo "<pre>ImbaManagerMessage Test:\n" . $output . "</pre>";
?>