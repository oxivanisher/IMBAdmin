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
                //header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
                header("location: " . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
            }
            break;

        case "checkCaptcha":
            /**
             * Do some basic tests
             */
            if (ImbaUserContext::getNeedToRegister()) {
                ImbaConstants::loadSettings();
                require_once 'Libs/reCaptcha/recaptchalib.php';
                $resp = null;
                $error = null;

                /**
                 * Check if the recaptcha is ok
                 */
                $resp = recaptcha_check_answer(
                        ImbaConstants::$SETTINGS["CAPTCHA_PRIVATE_KEY"], ImbaSharedFunctions::getIP(), $_POST["challenge"], $_POST["answer"]
                );
                $tmpOpenid = ImbaUserContext::getOpenIdUrl();
                if ($resp->is_valid) {
                    /**
                     * Check if all fields have content
                     */
                    if ((!empty($_POST["birthday"])) &&
                            (!empty($tmpOpenid)) &&
                            (!empty($_POST["firstname"])) &&
                            (!empty($_POST["lastname"])) &&
                            (!empty($_POST["sex"])) &&
                            (!empty($_POST["nickname"])) &&
                            (!empty($_POST["email"]))) {

                        $birthdate = explode(".", $_POST["birthday"]);

                        /**
                         * Set the new user
                         */
                        $newUser = $managerUser->getNew();
                        $newUser->setFirstname(trim($_POST["firstname"]));
                        $newUser->setLastname(trim($_POST["lastname"]));
                        $newUser->setSex(trim($_POST["sex"]));
                        $newUser->setNickname(trim($_POST["nickname"]));
                        $newUser->setEmail(trim($_POST["email"]));
                        $newUser->setBirthday($birthdate[0]);
                        $newUser->setBirthmonth($birthdate[1]);
                        $newUser->setBirthyear($birthdate[2]);
                        $newUser->setOpenId($tmpOpenid);
                        $newUser->setRole(ImbaConstants::$CONTEXT_NEW_USER_ROLE);

                        /**
                         * Save the new user
                         */
                        $managerUser->insert($newUser);
                        echo "Ok";
                    } else {
                        /**
                         * Something strange happend. Try to kick the user out of all sessions
                         */
                        //header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
                        header("location: " . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
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
                /**
                 * Something strange happend. Try to kick the user out of all sessions
                 */
                //header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
                header("location: " . ImbaConstants::$WEB_OPENID_AUTH_PATH . "?logout=true");
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
                $smarty->assign('publicKey', ImbaConstants::$SETTINGS["CAPTCHA_PUBLIC_KEY"]);
                $smarty->display('IMBAdminModules/RegisterForm.tpl');
            } else {
                /**
                 * User gets the welcome screen with the openid input field
                 */
                //$smarty->assign('registerurl', ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH);
                $smarty->assign('registerurl', ImbaConstants::$WEB_OPENID_AUTH_PATH);
                $smarty->display('IMBAdminModules/RegisterWelcome.tpl');
            }
    }
}
?>
