<?php

/**
 * Single point of Ajax entry
 *
 */
switch ($_POST["action"]) {
    case "messenger":
        include 'Ajax/Messenger.php';
        break;

    /**
     * Some other examples
    case "user":
        include 'Ajax/User.php';
        break;

    case "role":
        include 'Ajax/Role.php';
        break;

    case "chat":
        include 'Ajax/Chat.php';
        break;

    case "event":
        include 'Ajax/Event.php';
        break;
     */

    // TODO: use default case for event polling?
    default:
        break;
}
?>
