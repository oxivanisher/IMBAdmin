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
    echo "You cannot register while logged in.";
} else {
    /**
     * Load the database
     */
    $managerUser = ImbaManagerUser::getInstance();

    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();
    switch ($_POST["request"]) {
        case "registerme":
            /**
             * Show success an let him click a button which sends him trough the normal login procedure
             */
            if ($_SESSION["IUC_captchaState"] == "ok") {
                $_SESSION["IUC_captchaState"] = "";
                ImbaUserContext::getNeedToRegister(false);
                $smarty->display('IMBAdminModules/RegisterSuccess.tpl');
            } else {
                header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
            }
            break;

        case "checkCaptcha":
            if (ImbaUserContext::getNeedToRegister()) {
                ImbaConstants::loadSettings();
                require_once 'Libs/reCaptcha/recaptchalib.php';
                $resp = null;
                $error = null;

                $resp = recaptcha_check_answer(
                        ImbaConstants::$SETTINGS["captcha_private_key"], ImbaSharedFunctions::getIP(), $_POST["challenge"], $_POST["answer"]
                );
                $tmpOpenid = ImbaUserContext::getOpenIdUrl();
                if ($resp->is_valid) {
                    if ((!empty($_POST["birthday"])) &&
                            (!empty($tmpOpenid)) &&
                            (!empty($_POST["firstname"])) &&
                            (!empty($_POST["lastname"])) &&
                            (!empty($_POST["sex"])) &&
                            (!empty($_POST["nickname"])) &&
                            (!empty($_POST["email"]))) {

                        $birthdate = explode(".", $_POST["birthday"]);
                        $newUser = $managerUser->getNew();
                        $newUser->setFirstname($_POST["firstname"]);
                        $newUser->setLastname($_POST["lastname"]);
                        $newUser->setSex($_POST["sex"]);
                        $newUser->setNickname($_POST["nickname"]);
                        $newUser->setEmail($_POST["email"]);
                        $newUser->setBirthday($birthdate[0]);
                        $newUser->setBirthmonth($birthdate[1]);
                        $newUser->setBirthyear($birthdate[2]);
                        $newUser->setOpenId($tmpOpenid);
                        $newUser->setRole(ImbaConstants::$CONTEXT_NEW_USER_ROLE);
                        $managerUser->insert($newUser);
                        echo "Ok";
                    } else {
                        header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
                    }
                } else {
                    # set the error code so that we can display it
                    $error = $resp->error;
                    if ($error == "incorrect-captcha-sol") {
                        echo "Deine Eingabe war nicht korrekt!";
                    } else {
                        echo $error;
                    }
                }
                /**
                 * Check fucking everything here! NEVER THRUST A USER
                 * - query http://www.google.com/recaptcha/api/verify
                 */
                /**
                 * Then save the user
                 */
                $_SESSION["IUC_captchaState"] = "ok";
            } else {
                header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
            }
            break;

        default:
            if (ImbaUserContext::getNeedToRegister()) {
                ImbaConstants::loadSettings();
                $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());

//                require_once('Libs/reCaptcha/recaptchalib.php');
//                $resp = null;
//                $error = null;

                /**
                 * use $_SESSION["IUC_captchaState"] as control variable
                 */
                $_SESSION["IUC_captchaState"] = "unchecked";

                $smarty->assign('authPath', ImbaConstants::$WEB_OPENID_AUTH_PATH);
                $smarty->assign('indexPath', ImbaConstants::$WEB_ENTRY_INDEX_FILE);
                $smarty->assign('publicKey', ImbaConstants::$SETTINGS["captcha_public_key"]);
                $smarty->display('IMBAdminModules/RegisterForm.tpl');
            } else {
                /**
                 * User gets the welcome screen with the openid input field
                 */
                $smarty->assign('registerurl', ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH);
                $smarty->display('IMBAdminModules/RegisterWelcome.tpl');
            }
    }
}
?>
