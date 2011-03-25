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

            //ImbaSharedFunctions::killCookies();
            setcookie(session_id(), "", time() - 3600);
            session_destroy();
            session_write_close();
            $smarty->display('IMBAdminModules/RegisterAbort.tpl');
            break;
        case "registerme":
            /**
             * Put the user into the database an let him klick a button which sends him the normal login procedure
             */
            $smarty->display('IMBAdminModules/RegisterSuccess.tpl');
            break;
        case "checkCaptcha":
            if (ImbaUserContext::getNeedToRegister()) {
                /**
                 * The user needs to fill out the form
                 */
            }
            break;
        default:
            if (ImbaUserContext::getNeedToRegister()) {
                /**
                 * The user needs to fill out the captcha
                 */
                require_once('Libs/reCaptcha/recaptchalib.php');
                ImbaConstants::loadSettings();
                $publickey = ImbaConstants::$SETTINGS["captcha_public_key"];
                $privatekey = ImbaConstants::$SETTINGS["captcha_private_key"];

                $resp = null;
                $error = null;
                if ($_POST["recaptcha_response_field"]) {
                    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

                    if ($resp->is_valid) {
                        $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());
                        $smarty->display('IMBAdminModules/RegisterForm3.tpl');
                    } else {
                        # set the error code so that we can display it
                        $smarty->assign('captchaContent', recaptcha_get_html($publickey, $error));
                        $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());
                        $smarty->assign('return', $resp->error);
                        $smarty->display('IMBAdminModules/RegisterForm2.tpl');
                    }
                } else {
                    $smarty->assign('captchaContent', recaptcha_get_html($publickey, $error));
                    $smarty->assign('openid', ImbaUserContext::getOpenIdUrl());
                    $smarty->display('IMBAdminModules/RegisterForm2.tpl');
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
