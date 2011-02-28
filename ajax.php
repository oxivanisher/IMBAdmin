<?php

/**
 * Single point of Ajax entry
 *
 */
switch ($_POST["action"]) {
    case "messenger":
        include 'Ajax/Messenger.php';
        break;
    
    default:
        break;
}
?>
