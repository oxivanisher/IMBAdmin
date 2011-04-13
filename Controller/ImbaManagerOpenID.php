<?php

/**
 * There is a major rewrite going on here!
 */
/**
 * load the needed classes for OpenID operations
 */
/* tmp
  $tmpPath = getcwd();
  chdir("Libs/php-openid/");
  require_once "Auth/OpenID/Consumer.php";
  require_once "Auth/OpenID/FileStore.php";
  require_once "Auth/OpenID/SReg.php";
  require_once "Auth/OpenID/PAPE.php";
  // test oxi: require_once "Auth/OpenID.php";

  chdir($tmpPath);
 */
require_once "Libs/lightopenid/openid.php";

require_once "Controller/ImbaUserContext.php";
require_once "Controller/ImbaManagerDatabase.php";
require_once "Controller/ImbaManagerUser.php";
require_once "Model/ImbaUser.php";
require_once "ImbaConstants.php";

/**
 * set the requested PAPE policies
 */
/* tmp
  global $pape_policy_uris;
  $pape_policy_uris = array(
  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
  PAPE_AUTH_MULTI_FACTOR,
  PAPE_AUTH_PHISHING_RESISTANT
  );
 *
 */

/**
 * Description of ImbaManagerOpenID
 * 
 * - Handles OpneID verification, authentifications
 *
 */
class ImbaManagerOpenID {

    private static $lightOpenid = null;

    /**
     *
     * get Scheme
     */
    protected function getScheme() {
        $scheme = 'http';
        if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
            $scheme .= 's';
        }
        return $scheme;
    }

    /**
     *
     * get ReturnTo Address
     */
    protected function getReturnTo() {
        // this sould be like that, if the webserver would be set up correctly
        // return sprintf("%s://%s:%s%s/?authDone=true", $this->getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], dirname($_SERVER['PHP_SELF']));
        return sprintf("%s://%s/%s/ImbaAuth.php?authDone=true", $this->getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']));
    }

    /**
     *
     * get the trust root
     */
    protected function getTrustRoot() {
        // this sould be like that, if the webserver would be set up correctly
        // return sprintf("%s://%s:%s%s/", $this->getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], dirname($_SERVER['PHP_SELF']));
        return sprintf("%s://%s/%s/", $this->getScheme(), $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']));
    }

    /**
     * OpenID Auth
     */
    public function openidAuth($openid) {
        $this->lightOpenid = new LightOpenID;
        $this->lightOpenid->returnUrl = $this->getReturnTo();
        $this->lightOpenid->realm = $this->getTrustRoot();

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
        $this->lightOpenid->returnUrl = $this->getReturnTo();
        $this->lightOpenid->realm = $this->getTrustRoot();

        if ($this->lightOpenid->mode == 'cancel') {
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_CANCEL);
        } elseif ($this->lightOpenid->validate()) {
            return $this->lightOpenid->identity;
        }
    }

}
?>