<?php

// Extern Session start
session_start();

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerLog.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaManagerMultigaming.php';
//require_once 'Controller/ImbaManagerGame.php';
//require_once 'Controller/ImbaManagerGameCategory.php';
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
    $managerMultigaming = ImbaManagerMultigaming::getInstance();
//$managerGame = ImbaManagerGame::getInstance();
//$managerGameCategory = ImbaManagerGameCategory::getInstance();

    switch ($_POST["request"]) {

        /**
         * Role Management
         */
        case "role":
            $roles = $managerRole->selectAll();

            $smarty_roles = array();
            foreach ($roles as $role) {
                array_push($smarty_roles, array(
                    "id" => $role->getId(),
                    "handle" => $role->getHandle(),
                    "role" => $role->getRole(),
                    "name" => $role->getName(),
                    "icon" => $role->getIcon(),
                    "smf" => $role->getSmf(),
                    "wordpress" => $role->getWordpress()
                ));
            }
            $smarty->assign('roles', $smarty_roles);
            $smarty->display('IMBAdminModules/AdminRole.tpl');
            break;

        case "updaterole":
            $role = $managerRole->selectById($_POST["roleid"]);

            switch ($_POST["rolecolumn"]) {
                case "Role":
                    $role->setRole($_POST["value"]);
                    break;

                case "Handle":
                    $role->setHandle($_POST["value"]);
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

        case "addrole":
            $role = $managerRole->getNew();
            $role->setHandle($_POST["handle"]);
            $role->setRole($_POST["role"]);
            $role->setName($_POST["name"]);
            $role->setSmf($_POST["smf"]);
            $role->setWordpress($_POST["wordpress"]);
            $role->setIcon($_POST["icon"]);
            $managerRole->insert($role);
            break;


        /**
         * Game Management
         */
        case "game":
            $games = $managerMultigaming->selectAllGames();
            $categories = $managerMultigaming->selectAllCategories();

            $smarty_categories = array();
            foreach ($categories as $category) {
                array_push($smarty_categories, array(
                    'id' => $category->getId(),
                    'name' => $category->getName()
                ));
            }
            $smarty->assign('categories', $smarty_categories);

            $smarty_games = array();
            foreach ($games as $game) {
                $tmpCategories = array();

                /**
                 * Not jet implemented in imbaManagerMultigaming!
                 *
                  foreach ($game->getCategories() as $category) {
                  array_push($tmpCategories, $category->getId());
                  }
                 */
                array_push($smarty_games, array(
                    "id" => $game->getId(),
                    "name" => $game->getName(),
                    "comment" => $game->getComment(),
                    "icon" => $game->getIcon(),
                    "url" => $game->getUrl(),
                    "forumlink" => $game->getForumlink(),
                    "categoriesSelected" => $tmpCategories,
                ));
            }
            $smarty->assign('games', $smarty_games);
            $smarty->display('IMBAdminModules/AdminGame.tpl');
            break;

        case "updategame":
            $game = $managerMultigaming->selectGameById($_POST["gameid"]);

            switch ($_POST["gamecolumn"]) {
                case "Name":
                    $game->setName($_POST["value"]);
                    break;

                case "Icon":
                    $game->setIcon($_POST["value"]);
                    break;

                case "Url":
                    $game->setUrl($_POST["value"]);
                    break;

                case "Forumlink":
                    $game->setForumlink($_POST["value"]);
                    break;

                case "Comment":
                    $game->setComment($_POST["value"]);
                    break;

                /**
                 * FIXME: irgendwie muessen wir die spiele noch den kategorien zuweisen :/
                 * 
                 */
                default:
                    break;
            }

            $managerMultigaming->updateGame($game);
            echo $_POST["value"];
            break;

        case "deletegame":
            $tmpGame = $managerMultigaming->getNewGame();
            $tmpGame = setId($_POST["gameid"]);
            $managerMultigaming->deleteGame($tmpGame);
            break;

        case "addgame":

            $game = $managerMultigaming->getNewGame();
            $game->setName($_POST["name"]);
            $game->setComment($_POST["comment"]);
            $game->setIcon($_POST["icon"]);
            $game->setUrl($_POST["url"]);
            $game->setForumlink($_POST["forumlink"]);
            $managerMultigaming->insertGame($game);

            break;

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
            $smarty->display('IMBAdminModules/AdminSettings.tpl');
            break;

        case "updatesetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $setting = substr($_POST["settingid"], 3);
            $managerDatabase->query("UPDATE %s SET value='%s' WHERE name='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["value"], $setting));
            echo $_POST["value"];
            break;

        case "deletesetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $setting = substr($_POST["settingid"], 3);
            $managerDatabase->query("DELETE FROM %s WHERE name='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["settingid"]));
            break;

        case "addsetting":
            $managerDatabase = ImbaManagerDatabase::getInstance();
            $managerDatabase->query("INSERT INTO %s SET name='%s', value='%s';", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS, $_POST["name"], $_POST["value"]));
            break;


        /**
         * User Management
         */
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


            $smarty->display('IMBAdminModules/AdminViewedituser.tpl');
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

            $smarty->display('IMBAdminModules/AdminUserOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}