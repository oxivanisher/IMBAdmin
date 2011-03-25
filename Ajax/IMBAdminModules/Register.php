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

    switch ($request) {
        case "abort":
            /**
             * Cancle the registration, kill the session and let the user reload the page
             */
            ImbaUserContext::getNeedToRegister(false);
            $log = $managerLog->getNew();
            $log->setModule("Register");
            $log->setMessage(ImbaUserContext::getOpenIdUrl() . " aborted the registration");
            $log->setLevel(3);
            $managerLog->insert($log);
            $smarty->display('IMBAdminModules/RegisterAbort.tpl');

            //ImbaSharedFunctions::killCookies();
            setcookie(session_id(), "", time() - 3600);
            session_destroy();
            session_write_close();
            break;
        case "registerme":
            /**
             * Put the user into the database an let him klick a button which sends him the normal login procedure
             */
            if ($_SESSION["IUC_captchaState"] == "ok") {
                //FIXME: insert user here into database
                //if all the checks are ok and the user is allowed to log in, clear
                $_SESSION["IUC_captchaState"] = "";
                ImbaUserContext::getNeedToRegister(false);
                $smarty->display('IMBAdminModules/RegisterSuccess.tpl');
            }
            break;
        default:
            if (ImbaUserContext::getNeedToRegister()) {
                ImbaConstants::loadSettings();
                $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());

                require_once('Libs/reCaptcha/recaptchalib.php');
                $resp = null;
                $error = null;

                /**
                 * use $_SESSION["IUC_captchaState"] as control variable
                 */
                if (empty($_SESSION["IUC_captchaState"])) {
                    $_SESSION["IUC_captchaState"] = "unchecked";
                }

                $resp = recaptcha_check_answer(ImbaConstants::$SETTINGS["captcha_private_key"], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                $smarty->assign('captchaContent', 'Could not load captcha content.');
                if ($_SESSION["IUC_captchaState"] == "unchecked") {
                    /**
                     * The user needs to fill out the captcha
                     */
                    $_SESSION["IUC_captchaState"] = "checking";
                    $smarty->assign('captchaContent', recaptcha_get_html(ImbaConstants::$SETTINGS["captcha_public_key"], $error));
                    $smarty->display('IMBAdminModules/RegisterForm2.tpl');
                } else {

                    if ($resp->is_valid) {
                        $_SESSION["IUC_captchaState"] = "ok";
                        $smarty->display('IMBAdminModules/RegisterForm3.tpl');
                    } else {
                        # set the error code so that we can display it
                        $smarty->assign('captchaContent', recaptcha_get_html(ImbaConstants::$SETTINGS["captcha_public_key"], $error));
                        $smarty->assign('error', $resp->error);
                        $smarty->display('IMBAdminModules/RegisterForm2.tpl');
                    }
                }
            } else {
                /**
                 * User gets the welcome screen with the openid input field
                 */
                $smarty->assign('registerurl', ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_OPENID_AUTH_PATH);
                $smarty->display('IMBAdminModules/RegisterForm1.tpl');
            }
    }
}
?>
