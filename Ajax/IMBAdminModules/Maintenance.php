<?php

// Extern Session start
session_start();

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerLog.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn() && ImbaUserContext::getUserRole() >= 3) {
    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();
    $managerRole = ImbaManagerUserRole::getInstance();

    switch ($_POST["request"]) {

        /**
         * Settings Management
         */
        case "settings":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $settings = array();
            $managerDatabase->query("SELECT * FROM %s;", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS));
            while ($row = $managerDatabase->fetchRow()) {
                array_push($settings, array('name' => $row["name"], 'value' => $row["value"]));
            }
            $smarty->assign('settings', $settings);
            $smarty->display('IMBAdminModules/MaintenanceSettings.tpl');
            break;

        case "updatesetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $managerDatabase->query("UPDATE %s SET value='%s' WHERE name='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["value"], $_POST["settingid"]));
            echo $_POST["value"];
            break;

        case "deletesetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $managerDatabase->query("DELETE FROM %s WHERE name='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["settingid"]));
            break;

        case "addsetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $managerDatabase->query("INSERT INTO %s SET name='%s', value='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["name"], $_POST["value"]));
            break;


        /**
         * Mainenance Jobs
         */
        case "maintenance":
            $maintenenceJobs = array();
            $dbJobs = array();
            $userJobs = array();
            
            array_push($maintenenceJobs, array('handle' => 'clearLog', 'name' => 'Clear System Messages'));
            array_push($maintenenceJobs, array('handle' => 'findUnusedRoles', 'name' => 'Analyze User Roles'));
            array_push($maintenenceJobs, array('handle' => 'showSettings', 'name' => 'Show the $SETTINGS array'));
            array_push($dbJobs, array('handle' => 'backupDatabase', 'name' => 'Create Database dump'));
            array_push($dbJobs, array('handle' => 'showDatabaseBackups', 'name' => 'Show Database backups'));
            array_push($userJobs, array('handle' => 'findIncompleteUsers', 'name' => 'Find incomplete User Profiles'));
            array_push($userJobs, array('handle' => 'fakeUsersOnline', 'name' => 'Fake some Users online status'));
            array_push($userJobs, array('handle' => 'kickAllOffline', 'name' => 'Kick ass Users offline status'));

            $smarty->assign('maintenanceJobs', $maintenenceJobs);
            $smarty->assign('dbJobs', $dbJobs);
            $smarty->assign('userJobs', $userJobs);
            $smarty->display('IMBAdminModules/MaintenanceMaintenance.tpl');
            break;

        case "runMaintenanceJob":
            $managerLog = ImbaManagerLog::getInstance();
            $log = $managerLog->getNew();
            $log->setModule("Maintenance");
            switch ($_POST["jobHandle"]) {
                case "findUnusedRoles":
                    $log->setMessage("Analyze User Roles");
                    $smarty->assign('name', $log->getMessage());
                    $log->setLevel(2);

                    $users = $managerUser->selectAllUser();
                    $roles = $managerRole->selectAll();
                    $tmpRoles = array();
                    $counts = array();
                    foreach ($users as $user) {
                        if (!in_array($user->getRole(), $tmpRoles)) {
                            array_push($tmpRoles, $user->getRole());
                            $counts[$user->getRole()] = 1;
                        }
                        $counts[$user->getRole()]++;
                    }

                    $return = "";
                    foreach ($roles as $role) {
                        if ($counts[$role->getRole()]) {
                            $count = $counts[$role->getRole()];
                        } else {
                            $count = 0;
                        }
                        $return .= $role->getRole() . " " . $role->getName() . ": " . $count . "<br />";
                    }

                    $managerLog->insert($log);
                    $smarty->assign('message', $return);
                    break;

                case "findIncompleteUsers":
                    $log->setMessage("Find incomplete User Profiles");
                    $smarty->assign('name', $log->getMessage());
                    $log->setLevel(2);

                    $return = "<b>These Members are missing at least one of the following fields:</b><br />";
                    $return .= "<i>Nickname, Firstname, Lastname, OpenId</i><br /><br />";
//                    $incompleteUsers = array();
                    foreach ($managerUser->selectAllUser() as $user) {
                        $count = 0;

                        if ($user->getNickname() == null)
                            $count++;
                        if ($user->getFirstname() == null)
                            $count++;
                        if ($user->getLastname() == null)
                            $count++;
                        if ($user->getOpenId() == null)
                            $count++;

                        if ($count > 0) {
                            $return .= $user->getNickname() . ": " . $count . "<br />";
                        }
                    }



                    $managerLog->insert($log);
                    $smarty->assign('message', $return);
                    break;

                case "clearLog":
                    $managerLog = ImbaManagerLog::getInstance();
                    $managerLog->clearAll();

                    $smarty->assign('name', 'Clear System Messages');
                    $smarty->assign('message', 'Messages cleared!<br />');
                    break;

                case "showDatabaseBackups":
                    $backupPath = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER["PHP_SELF"]) . "/Backup/";

                    $tmpOut = "<h4>Files in ".$backupPath.":</h4>";
                    if ($handle = opendir($backupPath)) {
                        $filesArray = array();
                        while (false !== ($file = readdir($handle))) {
                            if ($file != "." && $file != ".." && $file != ".htaccess"  && $file != ".gitignore") {
                                array_push($filesArray, '<a href="Backup/' . $file . '">' . $file . '</a>');
                            }
                        }
                        closedir($handle);
                        rsort($filesArray);
                        $firstBool = true;
                        foreach ($filesArray as $file) {
                            if ($firstBool)
                                $tmpOut .= "&gt; <b>";
                            else
                                $tmpOut .= "&nbsp;&nbsp;&nbsp;";
                            $tmpOut .= $file;
                            if ($firstBool) {
                                $tmpOut .= "</b> &lt;";
                                $firstBool = false;
                            }
                            $tmpOut .= "<br />";
                        }
                    }

                    $smarty->assign('name', 'Show Database backups');
                    $smarty->assign('message', $tmpOut);
                    break;

                case "backupDatabase":
                    $backupPath = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER["PHP_SELF"]) . "/Backup/";
                    $backupFile = ImbaConstants::$DATABASE_DB . "_" . date("Y-m-d-H-i-s") . '.gz';
                    $command = "mysqldump --opt" .
                            " -h " . ImbaConstants::$DATABASE_HOST .
                            " -u " . ImbaConstants::$DATABASE_USER .
                            " -p" . ImbaConstants::$DATABASE_PASS .
                            " " . ImbaConstants::$DATABASE_DB . " | gzip > /tmp/" . $backupFile;
                    system($command);
                    system("mv /tmp/" . $backupFile . " " . $backupPath);

                    $smarty->assign('name', 'Backup Database');
                    $smarty->assign('message', 'You can download the actual dump from here:<br /><a href="Backup/' . $backupFile . '">' . $backupFile . '</a>');
                    break;

                case "showSettings":
                    $smarty->assign('name', 'Show the $SETTINGS array');
                    ImbaConstants::loadSettings();
                    $message = "";
                    foreach (ImbaConstants::$SETTINGS as $key => $value) {
                        $message .= $key . ": " . $value . "<br />";
                    }

                    $smarty->assign('message', $message);
                    break;

                default:
                    $smarty->assign('name', $_POST["jobHandle"]);
                    $smarty->assign('message', 'unknown job: ' . $_POST["jobHandle"]);
            }
            $smarty->display('IMBAdminModules/MaintenanceMaintenanceRunJob.tpl');
            break;


        /**
         * System Statistics
         */
        case "statistics":
            $managerLog = ImbaManagerLog::getInstance();
            $managerMessage = ImbaManagerMessage::getInstance();

            $smarty->assign('users', count($managerUser->selectAllUser()));
            $smarty->assign('userroles', count($managerRole->selectAll()));

            $logCount = 0;
            $sessions = array();
            foreach ($managerLog->selectAll() as $logEntry) {
                if (!in_array($logEntry->getSession(), $sessions)) {
                    array_push($sessions, $logEntry->getSession());
                    $logCount++;
                }
            }
            $smarty->assign('usersessions', $logCount);

            $smarty->assign('messages', $managerMessage->returnNumberOfMessages());
            $smarty->assign('logs', count($managerLog->selectAll()));

            $smarty->display('IMBAdminModules/MaintenanceStatistics.tpl');
            break;


        /**
         * Log viewer
         */
        case "viewlogdetail":
            $managerLog = ImbaManagerLog::getInstance();

            /**
             * Get log entry
             */
            $log = $managerLog->selectId($_POST["id"]);

            /**
             * Get user
             */
            if ($log->getUser() == null) {
                $user = "Anonymous";
            } else {
                $user = $managerUser->selectByOpenId($log->getUser())->getNickname();
            }

            /**
             * Get city trough GeoIP
             */
            include("Libs/GeoIP/GeoIP.php");
            // uncomment for Shared Memory support
            // geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
            // $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);
            $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat", GEOIP_STANDARD);
            $record = geoip_record_by_addr($gi, $log->getIp());
            $smarty->assign('city', $record->city . ", " . $record->country_name);
            geoip_close($gi);
            $smarty->assign('ip', $log->getIp());

            $smarty->assign('date', ImbaSharedFunctions::genTime($log->getTimestamp()));
            $smarty->assign('age', ImbaSharedFunctions::getAge($log->getTimestamp()));
            $smarty->assign('openid', $log->getUser());
            $smarty->assign('id', $log->getId());
            $smarty->assign('user', $user);
            $smarty->assign('module', $log->getModule());
            $smarty->assign('session', $log->getSession());
            $smarty->assign('message', $log->getMessage());
            $smarty->assign('level', $log->getLevel());

            $sessionLogs = $managerLog->selectAll();
            $smarty_logs = array();
            foreach ($sessionLogs as $sessionLog) {
                if ($sessionLog->getSession() == $log->getSession()) {
                    $username = "Anonymous";
                    if ($sessionLog->getUser() != "") {
                        $username = $managerUser->selectByOpenId($sessionLog->getUser())->getNickname();
                    }

                    array_push($smarty_logs, array(
                        'id' => $sessionLog->getId(),
                        'date' => ImbaSharedFunctions::getAge($sessionLog->getTimestamp()),
                        'module' => $sessionLog->getModule(),
                        'message' => $sessionLog->getMessage(),
                        'level' => $sessionLog->getLevel()
                    ));
                }
            }
            $smarty->assign('logs', $smarty_logs);

            $smarty->display('IMBAdminModules/MaintenanceLogViewdetail.tpl');
            break;

        default:
            $managerLog = ImbaManagerLog::getInstance();
            $logs = $managerLog->selectAll();

            $smarty_logs = array();
            foreach ($logs as $log) {
                if ($log->getLevel() <= 1) {
                    $tmpUser = $managerUser->selectByOpenId($log->getUser());
                    if ($tmpUser != null) {
                        $username = $tmpUser->getNickname();
                    } else {
                        $username = "Anonymous";
                    }

                    if (count($log->getMessage() > 40)) {
                        $tmpMessage = substr($log->getMessage(), 0, 37) . "...";
                    } else {
                        $tmpMessage = $log->getMessage();
                    }

                    array_push($smarty_logs, array(
                        'id' => $log->getId(),
                        'timestamp' => $log->getTimestamp(),
                        'age' => ImbaSharedFunctions::getAge($log->getTimestamp()),
                        'user' => $username,
                        'module' => $log->getModule(),
                        'message' => $tmpMessage,
                        'lvl' => $log->getLevel()
                    ));
                }
            }
            $smarty->assign('logs', $smarty_logs);

            $smarty->display('IMBAdminModules/MaintenanceLog.tpl');
            break;
    }
} else {
    echo "Not logged in";
}