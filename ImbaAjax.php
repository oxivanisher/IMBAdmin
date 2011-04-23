<?php

/**
 * Single point of Ajax entry
 *
 */
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerLog.php';
require_once 'Controller/ImbaUserContext.php';

switch ($_POST["action"]) {
    case "messenger":
        include 'Ajax/Messenger.php';
        break;

    case "user":
        include 'Ajax/User.php';
        break;

    case "portal":
        include 'Ajax/Portal.php';
        break;

    case "navigation":
        include 'Ajax/Navigation.php';
        break;

    case "game":
        session_start();
        if (ImbaUserContext::getLoggedIn()) {
            $managerUser = ImbaManagerUser::getInstance();
            $managerUser->setMeOnline();
            unset($managerUser);
        }

        /**
         * Load my module navigation
         */
        $moduleConfigFile = "Ajax/IMBAdminGames/" . $_POST["game"] . ".Navigation.php";
        if (file_exists($moduleConfigFile)) {
            include $moduleConfigFile;
        }
        $moduleFile = "Ajax/IMBAdminGames/" . $_POST["game"] . ".php";
        if (file_exists($moduleFile)) {
            include $moduleFile;
        }

        break;

    case "module":
        session_start();
        if (ImbaUserContext::getLoggedIn()) {
            $managerUser = ImbaManagerUser::getInstance();
            $managerUser->setMeOnline();
            unset($managerUser);
        }

        /**
         * Load my module navigation
         */
        $moduleConfigFile = "Ajax/IMBAdminModules/" . $_POST["module"] . ".Navigation.php";
        if (file_exists($moduleConfigFile)) {
            include $moduleConfigFile;
        }
        $moduleFile = "Ajax/IMBAdminModules/" . $_POST["module"] . ".php";
        if (file_exists($moduleFile)) {
            include $moduleFile;
        }

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
