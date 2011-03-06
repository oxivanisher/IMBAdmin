<?php

/**
 * Single point of Ajax entry
 *
 */

/**
 * FIXME: Kill me after bugfixing
 */
if (!isset($_POST["action"]))
    $_POST = $_GET;

switch ($_POST["action"]) {
    case "messenger":
        include 'Ajax/Messenger.php';
        break;

    case "user":
        include 'Ajax/User.php';
        break;

    case "navigation":
        include 'Ajax/Navigation.php';
        break;
    
    case "mod_user":
        include 'Ajax/Content/User.php';
        break;

    /**
     * Some other examples
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
