<?php

// Extern Session start
session_start();

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerLog.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
//require_once 'Controller/ImbaManagerMultigaming.php';
require_once 'Controller/ImbaManagerGame.php';
require_once 'Controller/ImbaManagerGameCategory.php';
require_once 'Controller/ImbaManagerGameProperty.php';
require_once 'Controller/ImbaManagerPortal.php';
require_once 'Controller/ImbaManagerPortalEntry.php';
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
    //$managerMultigaming = ImbaManagerMultigaming::getInstance();
    $managerGame = ImbaManagerGame::getInstance();
    $managerGameCategory = ImbaManagerGameCategory::getInstance();
    $managerGameProperty = ImbaManagerGameProperty::getInstance();
    $managerPortal = ImbaManagerPortal::getInstance();
    $managerPortalEntry = ImbaManagerPortalEntry::getInstance();

    switch ($_POST["request"]) {
        /**
         * Portal Management
         */
        case "portal";
            
            $smarty->display('IMBAdminModules/AdminPortalOverview.tpl');
            break;

        /**
         * Navigation Entries Management
         */
         case "naventry";
             
            $smarty->display('IMBAdminModules/AdminNavigationEntries.tpl');
            break;
       
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
            $games = $managerGame->selectAll();

            $smarty_categories = array();
            $smarty_games = array();
            foreach ($games as $game) {
                array_push($smarty_games, array(
                    "id" => $game->getId(),
                    "name" => $game->getName(),
                    "comment" => $game->getComment(),
                    "icon" => $game->getIcon(),
                    "url" => $game->getUrl(),
                    "forumlink" => $game->getForumlink()
                ));
            }
            $smarty->assign('games', $smarty_games);
            $smarty->display('IMBAdminModules/AdminGame.tpl');
            break;

        case "viewgamedetail":
            $game = $managerGame->selectById($_POST["id"]);

            $smarty->assign("id", $game->getId());
            $smarty->assign("name", $game->getName());
            $smarty->assign("comment", $game->getComment());
            $smarty->assign("icon", $game->getIcon());
            $smarty->assign("url", $game->getUrl());
            $smarty->assign("forumlink", $game->getForumlink());

            $smarty_categories = array();
            foreach ($managerGameCategory->selectAll() as $category) {
                $selected = "false";
                foreach ($game->getCategories() as $selCategory) {
                    if ($selCategory->getId() == $category->getId()) {
                        $selected = "true";
                    }
                }

                array_push($smarty_categories, array(
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'selected' => $selected
                ));
            }
            $smarty->assign('categories', $smarty_categories);

            $smarty_properties = array();
            foreach ($game->getProperties() as $property) {
                array_push($smarty_properties, array(
                    'id' => $property->getId(),
                    'name' => $property->getProperty()
                ));
            }
            $smarty->assign('properties', $smarty_properties);

            $smarty->display('IMBAdminModules/AdminGameDetail.tpl');
            break;

        case "addpropertytogame":
            try {
                $property = ImbaManagerGameProperty::getInstance()->getNew();
                $property->setGameId($_POST["gameid"]);
                $property->setProperty($_POST["property"]);
                ImbaManagerGameProperty::getInstance()->insert($property);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;

        case "deletegameproperty":
            try {
                ImbaManagerGameProperty::getInstance()->delete($_POST["gamepropertyid"]);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;
        case "updategame":
            try {
                $game = $managerGame->selectById($_POST["gameid"]);
                $game->setName($_POST["name"]);
                $game->setIcon($_POST["icon"]);
                $game->setComment($_POST["comment"]);
                $game->setUrl($_POST["url"]);
                $game->setForumlink($_POST["forumlink"]);
                $categories = array();
                foreach ($_POST["myGameCategories"] as $categoryId) {
                    array_push($categories, ImbaManagerGameCategory::getInstance()->selectById($categoryId));
                }
                $game->setCategories($categories);

                $managerGame->update($game);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            break;

        case "deletegame":
            $managerGame->delete($_POST["gameid"]);
            break;

        case "addgame":

            $game = $managerGame->getNew();
            $game->setName($_POST["name"]);
            $game->setComment($_POST["comment"]);
            $game->setIcon($_POST["icon"]);
            $game->setUrl($_POST["url"]);
            $game->setForumlink($_POST["forumlink"]);
            $managerGame->insert($game);

            break;

        /**
         * GameCategories Management
         */
        case "gamecategory":
            $categories = $managerGameCategory->selectAll();

            $smarty_categories = array();
            foreach ($categories as $category) {
                array_push($smarty_categories, array(
                    'id' => $category->getId(),
                    'name' => $category->getName()
                ));
            }
            $smarty->assign('categories', $smarty_categories);

            $smarty->display('IMBAdminModules/AdminGameCategory.tpl');
            break;

        case "updategamecategory":
            $category = $managerGameCategory->selectById($_POST["categoryid"]);

            switch ($_POST["categorycolumn"]) {
                case "Name":
                    $category->setName($_POST["value"]);
                    break;

                default:
                    break;
            }

            $managerGameCategory->update($category);
            echo $_POST["value"];
            break;

        case "deletegamecategory":
            $managerGameCategory->delete($_POST["categoryid"]);
            break;

        case "addgamecategory":

            $category = $managerGameCategory->getNew();
            $category->setName($_POST["name"]);
            $managerGameCategory->insert($category);

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

            $allroles = array();
            foreach ($managerRole->selectAll() as $role) {
                array_push($allroles, array("id" => $role->getId(), "name" => $role->getName(), "role" => $role->getRole()));
            }
            $smarty->assign('allroles', $allroles);
            $smarty->assign('role', $user->getRole());

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

            $user->setRole($_POST["myProfileRole"]);

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