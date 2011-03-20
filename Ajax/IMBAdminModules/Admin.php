<?php

// Extern Session start
session_start();

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaLogger.php';
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
if (ImbaUserContext::getLoggedIn() && ImbaUserContext::getUserRole() >= 9) {
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
        case "role":
            $roles = $managerRole->selectAll();

            $smarty_roles = array();
            foreach ($roles as $role) {
                array_push($smarty_roles, array(
                    "id" => $role->getId(),
                    "role" => $role->getRole(),
                    "name" => $role->getName(),
                    "icon" => $role->getIcon(),
                    "smf" => $role->getSmf(),
                    "wordpress" => $role->getWordpress()
                ));
            }
            $smarty->assign('roles', $smarty_roles);
            $smarty->display('ImbaAjaxAdminRole.tpl');
            break;

        case "updaterole":
            $role = $managerRole->selectById($_POST["roleid"]);

            switch ($_POST["rolecolumn"]) {
                case "Role":
                    $role->setRole($_POST["value"]);
                    break;

                case "Name":
                    $role->setName($_POST["value"]);
                    break;

                case "Icon":
                    $role->setIcon($_POST["value"]);
                    break;

                case "SMF":
                    $role->setSmf($_POST["value"]);
                    break;

                case "Wordpress":
                    $role->setWordpress($_POST["value"]);
                    break;

                default:
                    break;
            }

            $managerRole->update($role);
            echo $_POST["value"];
            break;

        case "deleterole":
            $managerRole->delete($_POST["roleid"]);
            break;

        case "settings":
            $smarty->display('ImbaAjaxAdminSettings.tpl');
            break;

        case "statistics":
            $managerLog = ImbaLogger::getInstance();
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

            $smarty->display('ImbaAjaxAdminStatistics.tpl');
            break;

        case "log":
            $managerLog = ImbaLogger::getInstance();
            $logs = $managerLog->selectAll();

            $smarty_logs = array();
            foreach ($logs as $log) {
                $username = "Anonymous";
                if ($log->getUser() != "") {
                    $username = $managerUser->selectByOpenId($log->getUser())->getNickname();
                }

                array_push($smarty_logs, array(
                    'id' => $log->getId(),
                    'timestamp' => $log->getTimestamp(),
                    'age' => ImbaSharedFunctions::getAge($log->getTimestamp()),
                    'user' => $username,
                    'module' => $log->getModule(),
                    'message' => $log->getMessage(),
                    'lvl' => $log->getLevel()
                ));
            }
            $smarty->assign('logs', $smarty_logs);

            $smarty->display('ImbaAjaxAdminLog.tpl');
            break;

        case "viewlogdetail":
            $managerLog = ImbaLogger::getInstance();

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

            $smarty->display('ImbaAjaxAdminLogViewdetail.tpl');
            break;

        case "updatuser":
            $user = new ImbaUser();
            $user = $managerUser->selectByOpenId($_POST["myProfileOpenId"]);
            $user->setOpenId($_POST["myProfileOpenId"]);
            $user->setSex($_POST["myProfileSex"]);
            $user->setMotto($_POST["myProfileMotto"]);

            $role = $managerRole->selectByRole($_POST["myProfileRole"]);
            $user->setRole($role);

            $user->setBirthmonth($_POST["myProfileBirthmonth"]);
            $user->setBirthday($_POST["myProfileBirthday"]);
            $user->setLastname($_POST["myProfileLastname"]);
            $user->setFirstname($_POST["myProfileFirstname"]);
            $user->setMotto($_POST["myProfileMotto"]);
            $user->setUsertitle($_POST["myProfileUsertitle"]);
            $user->setAvatar($_POST["myProfileAvatar"]);
            $user->setWebsite($_POST["myProfileWebsite"]);
            $user->setNickname($_POST["myProfileNickname"]);
            $user->setEmail($_POST["myProfileEmail"]);
            $user->setSkype($_POST["myProfileSkype"]);
            $user->setIcq($_POST["myProfileIcq"]);
            $user->setMsn($_POST["myProfileMsn"]);
            $user->setSignature($_POST["myProfileSignature"]);
            try {
                $managerUser->update($user);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;

        case "viewedituser":
            $user = $managerUser->selectByOpenId($_POST["openid"]);

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', $user->getLastname());
            $smarty->assign('shortlastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday() . "." . $user->getBirthmonth() . "." . $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('email', $user->getEmail());
            $smarty->assign('website', $user->getWebsite());
            $smarty->assign('motto', $user->getMotto());
            $smarty->assign('usertitle', $user->getUsertitle());
            $smarty->assign('avatar', $user->getAvatar());
            $smarty->assign('signature', $user->getSignature());
            $smarty->assign('openid', $user->getOpenid());
            $smarty->assign('lastonline', ImbaSharedFunctions::getNiceAge($user->getLastonline()));

            if (strtolower($user->getSex()) == "m") {
                $smarty->assign('sex', 'Images/male.png');
            } else if (strtolower($user->getSex()) == "w" || strtolower($user->getSex()) == "f") {
                $smarty->assign('sex', 'Images/female.png');
            } else {
                $smarty->assign('sex', '');
            }

            $role = $managerRole->selectByRole($user->getRole());

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());


            $smarty->display('ImbaAjaxAdminViewedituser.tpl');
            break;

        case "updateuserprofile":
            $user = new ImbaUser();
            $user = $managerUser->selectByOpenId($_POST["myProfileOpenId"]);
            $user->setOpenId($_POST["myProfileOpenId"]);
            $user->setMotto($_POST["myProfileMotto"]);
            $user->setUsertitle($_POST["myProfileUsertitle"]);
            $user->setAvatar($_POST["myProfileAvatar"]);
            $user->setWebsite($_POST["myProfileWebsite"]);
            $user->setNickname($_POST["myProfileNickname"]);
            $user->setEmail($_POST["myProfileEmail"]);
            $user->setSkype($_POST["myProfileSkype"]);
            $user->setIcq($_POST["myProfileIcq"]);
            $user->setMsn($_POST["myProfileMsn"]);
            $user->setSignature($_POST["myProfileSignature"]);

            $birthdate = explode(".", $_POST["myProfileBirthday"]);
            $user->setBirthday($birthdate[0]);
            $user->setBirthmonth($birthdate[1]);
            $user->setBirthyear($birthdate[2]);

            try {
                $managerUser->update($user);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;

        case "runMaintenanceJob":
            $managerLog = ImbaLogger::getInstance();
            $log = $managerLog->getNew();
            $log->setModule("Admin");
            $log->setLevel(1);
            switch ($_POST["jobHandle"]) {
                case "findUnusedRoles":
                    $log->setMessage("Find unused user Roles");

                    $smarty->assign('name', $log->getMessage());
                    $smarty->assign('message', 'cleared');
                    break;

                case "findIncompleteUsers":
                    $log->setMessage("Find incomplete User Profiles");

                    $smarty->assign('name', $log->getMessage());
                    $smarty->assign('message', 'cleared');
                    break;

                case "clearLog":
                    $managerLog = ImbaLogger::getInstance();
                    $managerLog->clearAll();

                    $smarty->assign('name', 'Clear System Messages');
                    $smarty->assign('message', 'cleared');
                    break;
                default:
                    $smarty->assign('name', $_POST["jobHandle"]);
                    $smarty->assign('message', 'unknown job: ' . $_POST["jobHandle"]);
            }
            $managerLog->insert($log);
            $smarty->display('ImbaAjaxAdminMaintenanceRunJob.tpl');
            break;

        case "maintenance":

            $maintenenceJobs = array();

            array_push($maintenenceJobs, array('handle' => 'clearLog', 'name' => 'Clear System Messages'));
            array_push($maintenenceJobs, array('handle' => 'findUnusedRoles', 'name' => 'Find unused user Roles'));
            array_push($maintenenceJobs, array('handle' => 'findIncompleteUsers', 'name' => 'Find incomplete User Profiles'));

            $smarty->assign('jobs', $maintenenceJobs);
            $smarty->display('ImbaAjaxAdminMaintenance.tpl');
            break;

        default:
            $users = $managerUser->selectAllUser(ImbaUserContext::getOpenIdUrl());

            $smarty_users = array();
            foreach ($users as $user) {
                array_push($smarty_users, array(
                    'nickname' => $user->getNickname(),
                    'openid' => $user->getOpenID(),
                    'lastonline' => ImbaSharedFunctions::getNiceAge($user->getLastonline()),
                    'role' => $managerRole->selectByRole($user->getRole())->getName()
                ));
            }
            $smarty->assign('susers', $smarty_users);

            $smarty->display('ImbaAjaxAdminUserOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}