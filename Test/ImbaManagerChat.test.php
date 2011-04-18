<?php

chdir("../");
require_once 'Controller/ImbaManagerChatChannel.php';
require_once 'Controller/ImbaManagerChatMessage.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaChatChannel.php';

/**
 * Test login
 */
/**
 * Fucking hell, am I dirty!
 */
session_start();
ImbaUserContext::setLoggedIn(true);
ImbaUserContext::setOpenIdUrl("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
ImbaUserContext::setUserRole(3);

/**
 * Prepare Variables
 */
$managerChatChannel = ImbaManagerChatChannel::getInstance();
$managerChatMessage = ImbaManagerChatMessage::getInstance();
$managerDatabase = ImbaManagerDatabase::getInstance();
$output = "";

/**
 * Reset Table
 */
$managerDatabase->query("TRUNCATE TABLE `oom_openid_chatchannels` ");

/**
 * Insert channels
 */
try {
    $channel = new ImbaChatChannel();
    $channel->setName("Brachland");
    $allowed = array();
    for ($i = 0; $i < 4; $i++) {
        $a = $i >= 1;
        array_push($allowed, array("role" => $i, "allowed" => $a));
    }
    $channel->setAllowed(json_encode($allowed));
    $managerChatChannel->insert($channel);


    $channel = new ImbaChatChannel();
    $channel->setName("Admin");
    $allowed = array();
    array_push($allowed, array("role" => 3, "allowed" => true));

    $channel->setAllowed(json_encode($allowed));
    $managerChatChannel->insert($channel);

    $output.= "ImbaManagerChatChannel insert working.\n";
} catch (Exception $e) {
    $output.= "Error at insert.\n";
}

/**
 * Select All
 */
try {
    $channels = $managerChatChannel->selectAll();

    if (count($channels) == 2) {
        $output.= "ImbaManagerChatChannel selectAll (" . count($channels) . ")  working.\n";
    } else {
        $output.= "ImbaManagerChatChannel selectAll not (" . count($channels) . ") working.\n";
    }
} catch (Exception $e) {
    $output.= "Error at Select All.\n";
}

echo "<pre>ImbaManagerChatChannel Test:\n" . $output . "</pre>";

$output = "";

/**
 * Select All
 */
try {
    $channels = $managerChatChannel->selectAll();
    $messages = $managerChatMessage->selectAllByChannel($channels[0]);
    if (count($messages) > 0) {
        $output.= "ImbaManagerChatMessage selectAll working.\n";
    } else {
        $output.= "ImbaManagerChatMessage selectAll not working.\n";
    }
} catch (Exception $e) {
    $output.= "Error at Select All.\n";
}

echo "<pre>ImbaManagerChatMessage Test:\n" . $output . "</pre>";
?>
