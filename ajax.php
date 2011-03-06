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

//FIXME: I am just temporary here for testing purposes
$_POST["module"] = "User";


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
        /**
         * This block will be the same for every module
         * $tmpModule = "User"
         */
        $tmpModule = 'User';
        echo "ladida";
        echo "<div id='ImbaContentContainer'>";
        include "Ajax/Content/" . $tmpModule . ".php";
        echo "</div>";
        
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
