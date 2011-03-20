<?php

// Extern Session start

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();
    $roleManager = ImbaManagerUserRole::getInstance();

    switch ($_POST["request"]) {
        case "editmyprofile":
            $user = $managerUser->selectByOpenId(ImbaUserContext::getOpenIdUrl());

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', $user->getLastname());
            $smarty->assign('shortlastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
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

            $role = $roleManager->selectById($user->getRole()->getId());

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());


            $smarty->display('ImbaAjaxUserMyprofile.tpl');
            break;

        case "updatemyprofile":
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
            try {
                $managerUser->update($user);
                echo "Ok";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;

        case "viewprofile":
            if (isset($_POST["payLoad"]))
                $_POST["openid"] = $_POST["payLoad"];

            $user = $managerUser->selectByOpenId($_POST["openid"]);

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('website', $user->getWebsite());
            $smarty->assign('motto', $user->getMotto());
            $smarty->assign('avatar', $user->getAvatar());
            $smarty->assign('openid', $user->getOpenid());
            $smarty->assign('usertitle', $user->getUsertitle());
            $smarty->assign('signature', $user->getSignature());
            $smarty->assign('lastonline', ImbaSharedFunctions::getNiceAge($user->getLastonline()));

            if (strtolower($user->getSex()) == "m") {
                $smarty->assign('sex', 'Images/male.png');
            } else if (strtolower($user->getSex()) == "w" || strtolower($user->getSex()) == "f") {
                $smarty->assign('sex', 'Images/female.png');
            } else {
                $smarty->assign('sex', '');
            }

            $role = $roleManager->selectByRole($user->getRole());
            var_dump($role); exit;

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());
            $smarty->assign('myownprofile', false);
            $smarty->display('ImbaAjaxUserViewprofile.tpl');
            break;

        case "viewmyprofile":
            if (isset($_POST["payLoad"]))
                $_POST["openid"] = $_POST["payLoad"];

            $user = $managerUser->selectByOpenId(ImbaUserContext::getOpenIdUrl());

            $smarty->assign('nickname', $user->getNickname());
            $smarty->assign('lastname', substr($user->getLastname(), 0, 1) . ".");
            $smarty->assign('firstname', $user->getFirstname());
            $smarty->assign('birthday', $user->getBirthday());
            $smarty->assign('birthmonth', $user->getBirthmonth());
            $smarty->assign('birthyear', $user->getBirthyear());
            $smarty->assign('icq', $user->getIcq());
            $smarty->assign('msn', $user->getMsn());
            $smarty->assign('skype', $user->getSkype());
            $smarty->assign('website', $user->getWebsite());
            $smarty->assign('motto', $user->getMotto());
            $smarty->assign('avatar', $user->getAvatar());
            $smarty->assign('openid', $user->getOpenid());
            $smarty->assign('usertitle', $user->getUsertitle());
            $smarty->assign('signature', $user->getSignature());
            $smarty->assign('lastonline', ImbaSharedFunctions::getNiceAge($user->getLastonline()));

            if (strtolower($user->getSex()) == "m") {
                $smarty->assign('sex', 'Images/male.png');
            } else if (strtolower($user->getSex()) == "w" || strtolower($user->getSex()) == "f") {
                $smarty->assign('sex', 'Images/female.png');
            } else {
                $smarty->assign('sex', '');
            }

            $role = $roleManager->selectById($user->getRole()->getId());

            $smarty->assign('role', $role->getName());
            $smarty->assign('roleIcon', $role->getIcon());
            $smarty->assign('myownprofile', true);

            $smarty->display('ImbaAjaxUserViewprofile.tpl');
            break;

        default:
            $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());

            $smarty_users = array();
            foreach ($users as $user) {
                array_push($smarty_users, array(
                    'nickname' => $user->getNickname(),
                    'openid' => $user->getOpenID(),
                    'lastonline' => ImbaSharedFunctions::getNiceAge($user->getLastonline()),
                    'jabber' => "",
                    'games' => "W E R"
                ));
            }
            $smarty->assign('susers', $smarty_users);

            $smarty->display('ImbaAjaxUserOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
