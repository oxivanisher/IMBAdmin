<?php

// Extern Session start
session_start();

$tmpPath = getcwd();
chdir("../");
require_once 'Model/ImbaMessage.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerDatabase.php';
chdir($tmpPath);

/**
 * Send a Message
 */
if (isset($_POST['sender']) && isset($_POST['reciever']) && isset($_POST['message'])) {
    $message = new ImbaMessage();
    $message->setSender($_POST['sender']);
    $message->setReceiver($_POST['reciever']);
    $message->setMessage($_POST['message']);
    $message->setTimestamp(Date("U"));
    $message->setXmpp(0);
    $message->setNew(1);
    $message->setSubject("Was soll hier rein?");

    try {
        $managerDatabase = new ImbaManagerDatabase(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
        $managerMessage = new ImbaManagerMessage($managerDatabase);
        $managerMessage->insert($message);

        echo "Message sent";
    } catch (Exception $ex) {
        echo "Error: " . $ex->getMessage();
    }
}

/**
 * Recieve Messages
 */
if (isset($_POST['loadMessages']) && isset($_POST['sender']) && isset($_POST['reciever'])) {
    $managerDatabase = new ImbaManagerDatabase(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerMessage = new ImbaManagerMessage($managerDatabase);
    $conversation = $managerMessage->selectConversation($_POST['sender'], $_POST['reciever']);

    $resultHTML = "";
    foreach ($conversation as $message) {
        $resultHTML .= "<div>" . date("d.m.y H:m:s", $message->getTimestamp()) . " " . $message->getSender() . ":" . $message->getMessage() . "</div>\n";
    }

    return $resultHTML;
}
?>
