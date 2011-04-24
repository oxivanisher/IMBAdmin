<?php

/**
 * Loading required libs
 */
require_once "Controller/ImbaUserContext.php";
require_once "Controller/ImbaManagerDatabase.php";
require_once "Controller/ImbaManagerUser.php";
require_once "Model/ImbaUser.php";
require_once "ImbaConstants.php";


$tmpPath = getcwd();
chdir("Libs/");
require_once "lightopenid/openid.php";
require_once "Zend/Oauth/Consumer.php";
chdir($tmpPath);

/**
 * Description of ImbaManagerOpenID
 * - Handles OpneID verification, authentifications
 */
class ImbaManagerOpenID {

    private static $lightOpenid = null;

    /**
     * OpenID Auth
     */
    public function openidAuth($openid) {
        $this->lightOpenid = new LightOpenID;
        $this->lightOpenid->verify_peer = $WEB_AUTH_SSL_CHECK;
        $this->lightOpenid->returnUrl = ImbaUserContext::getWaitingForVerify();
        $this->lightOpenid->realm = ImbaSharedFunctions::getTrustRoot();

        if (!$this->lightOpenid->mode) {
            if (isset($openid)) {
                $this->lightOpenid->identity = $openid;
                return $this->lightOpenid->authUrl();
            }
        }
    }

    /**
     * OpenID verify
     */
    public function openidVerify() {
        $this->lightOpenid = new LightOpenID;
        $this->lightOpenid->verify_peer = $WEB_AUTH_SSL_CHECK;
        $this->lightOpenid->returnUrl = ImbaUserContext::getWaitingForVerify();
        $this->lightOpenid->realm = ImbaSharedFunctions::getTrustRoot();

        if ($this->lightOpenid->mode == 'cancel') {
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_CANCEL);
        } elseif ($this->lightOpenid->validate()) {
            return $this->lightOpenid->identity;
        } else {
            return false;
        }
    }

}

?>