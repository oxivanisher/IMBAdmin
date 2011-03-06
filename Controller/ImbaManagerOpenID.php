<?php

/**
 * load the needed classes for OpenID operations
 */
$tmpPath = getcwd();
chdir("Libs/OpenId");
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once "Auth/OpenID/SReg.php";
require_once "Auth/OpenID/PAPE.php";
// test oxi: require_once "Auth/OpenID.php";

chdir($tmpPath);

require_once "Controller/ImbaUserContext.php";
require_once "Controller/ImbaManagerDatabase.php";
require_once "Controller/ImbaManagerUser.php";
require_once "Model/ImbaUser.php";
require_once "ImbaConstants.php";

/**
 * set the requested PAPE policies
 */
global $pape_policy_uris;
$pape_policy_uris = array(
    PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
    PAPE_AUTH_MULTI_FACTOR,
    PAPE_AUTH_PHISHING_RESISTANT
);

/**
 * Description of ImbaManagerOpenID
 * 
 * - Handles OpneID verification, authentifications
 *
 */
class ImbaManagerOpenID {

    /**
     *
     * Getting the storage for the OpenID auth system
     */
    protected function &getStore() {
        $store_path = null;
        if (function_exists('sys_get_temp_dir')) {
            $store_path = sys_get_temp_dir();
        } else {
            if (strpos(PHP_OS, 'WIN') === 0) {
                $store_path = $_ENV['TMP'];
                if (!isset($store_path)) {
                    $dir = 'C:\Windows\Temp';
                }
            } else {
                $store_path = @$_ENV['TMPDIR'];
                if (!isset($store_path)) {
                    $store_path = '/tmp';
                }
            }
        }
        $store_path .= DIRECTORY_SEPARATOR . '_php_consumer_test';

        if (!file_exists($store_path) &&
                !mkdir($store_path)) {
            print "Could not create the FileStore directory '$store_path'. " .
                    " Please check the effective permissions.";
            exit(0);
        }
        $r = new Auth_OpenID_FileStore($store_path);

        return $r;
    }

    /**
     *
     * get the Consumer
     */
    protected function &getConsumer() {
        $store = $this->getStore();
        $r = new Auth_OpenID_Consumer($store);
        return $r;
    }

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
        // FIXME: hier view anpassen?
        // 
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
     *
     * escape the thing
     */
    protected function escape($thing) {
        return htmlentities($thing);
    }

    /**
     *
     * auth's the openid
     */
    public function openidAuth($openid, $policy_uris, &$redirectUrl, &$formHtml) {
        $consumer = $this->getConsumer();

        if (!ImbaSharedFunctions::isValidURL($openid)) {
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_INVALID_URI);
        }

        // Begin the OpenID authentication process.
        $auth_request = $consumer->begin($openid);

        // No auth request means we can't begin OpenID.
        if (!$auth_request) {
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_REQUEST_FAILED);
        }

        $sreg_request = Auth_OpenID_SRegRequest::build(
                        // Required
                        array('nickname', 'email', 'fullname'),
                        // Optional
                        array('dob', 'gender', 'postalcode', 'country'));

        if ($sreg_request) {
            $auth_request->addExtension($sreg_request);
        }

        $pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
        if ($pape_request) {
            $auth_request->addExtension($pape_request);
        }

        // Redirect the user to the OpenID server for authentication.
        // Store the token for this authentication so we can verify the
        // response.
        // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
        // form to send a POST request to the server.
        if ($auth_request->shouldSendRedirect()) {
            $redirectUrl = $auth_request->redirectURL($this->getTrustRoot(), $this->getReturnTo());
            // If the redirect URL can't be built, display an error
            // message.
            if (Auth_OpenID::isFailure($redirectUrl)) {
                throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_REDIRECT_FAILED, $redirectUrl);
            }
        } else {
            // Generate form markup and render it.
            $form_id = 'openid_message';
            $formHtml = $auth_request->formMarkup($this->getTrustRoot(), $this->getReturnTo(), false, array('id' => $form_id, 'name' => $form_id));

            // Display an error if the form markup couldn't be generated;
            // otherwise, render the HTML.
            if (Auth_OpenID::isFailure($formHtml)) {
                throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_FORM_FAILED, $formHtml);
            }
        }
    }

    /**
     *
     * verify the OpenID
     */
    public function openidVerify(ImbaManagerDatabase $database) {
        $consumer = $this->getConsumer();

        // Complete the authentication process using the server's
        // response.
        $return_to = $this->getReturnTo();
        $response = $consumer->complete($return_to);

        // Check the response status.
        if ($response->status == Auth_OpenID_CANCEL) {
            // This means the authentication was cancelled.
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_CANCEL);
        } else if ($response->status == Auth_OpenID_FAILURE) {
            // Authentication failed; display the error message.
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_FAILURE);
        } else if ($response->status == Auth_OpenID_SUCCESS) {
            // This means the authentication succeeded; extract the
            // identity URL and Simple Registration data (if it was
            // returned).
            $openid = $response->getDisplayIdentifier();
            $esc_identity = $this->escape($openid);

            $userManager = new ImbaManagerUser($database);
            $currentUser = new ImbaUser();
            $currentUser = $userManager->selectByOpenId($esc_identity);

            $bFoundRole = false;

            if ($currentUser->getRole() != null) {
                ImbaUserContext::setLoggedIn(true);
                ImbaUserContext::setOpenIdUrl($esc_identity);
            } else {
                throw new Exception("Registrierung noch nicht implementiert");
                // TODO: Registriereung wieder einbauen
                /*
                  $applicant = 0;
                  $sql = "SELECT nickname,timestamp,state,answer FROM " . $GLOBALS[cfg][userapplicationtable] . " WHERE openid='" . $esc_identity . "';";
                  $sqlr = mysql_query($sql);
                  while ($row = mysql_fetch_array($sqlr)) {
                  $applicant = 1;
                  $appname = $row[nickname];
                  $appanswer = $row[answer];
                  $appstate = $row[state];
                  $appage = getNiceAge($row[timestamp]);
                  }

                  if ($applicant) {
                  $atmp = templGetFile("waiting.html");
                  $atmp = templReplText($atmp, "NICK", $appname);
                  $atmp = templReplText($atmp, "ANSWER", $appanswer);
                  $atmp = templReplText($atmp, "STATE", $appstate);
                  $atmp = templReplText($atmp, "AGE", $appage);
                  $GLOBALS[html] .= $atmp;
                  } else {
                  sysmsg("Unauthorized access / Banned! " . $esc_identity, 1);
                  }
                  return false;
                 */
            }

            // TODO: logging! (ich bin eingeloggt)

            if ($response->endpoint->canonicalID)
                $escaped_canonicalID = escape($response->endpoint->canonicalID);

            $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

            $sreg = $sreg_resp->contents($sreg_resp);

            // TODO: Irgendwann mal gucken, ob wir mehr Daten von den OpenId Providern bekommen
            /*
              if ($sreg['email'])
              throw new Exception("waaaaahhhhh! " . escape($sreg['email']));

              if (@$sreg['nickname'])
              $_SESSION[user][nickname] = escape($sreg['nickname']);

              if (@$sreg['fullname'])
              $_SESSION[user][fullname] = escape($sreg['fullname']);

              if (@$sreg['dob'])
              $_SESSION[user][dob] = escape($sreg['dob']);

              if (@$sreg['gender'])
              $_SESSION[user][gender] = escape($sreg['gender']);

              if (@$sreg['country'])
              $_SESSION[user][country] = escape($sreg['country']);

              if (@$sreg['language'])
              $_SESSION[user][language] = escape($sreg['language']);

              if (@$sreg['timezone'])
              $_SESSION[user][timezone] = escape($sreg['timezone']);

              if (@$sreg['postalcode'])
              $_SESSION[user][postalcode] = escape($sreg['postalcode']);
             */

            $pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);
            if ($pape_resp) {
                if ($pape_resp->auth_policies) {
                    $success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

                    foreach ($pape_resp->auth_policies as $uri) {
                        $escaped_uri = escape($uri);
                        $success .= "<li><tt>$escaped_uri</tt></li>";
                    }

                    $success .= "</ul>";
                } else {
                    $success .= "<p>No PAPE policies affected the authentication.</p>";
                }

                if ($pape_resp->auth_age) {
                    $age = escape($pape_resp->auth_age);
                    $success .= "<p>The authentication age returned by the " .
                            "server is: <tt>" . $age . "</tt></p>";
                }

                if ($pape_resp->nist_auth_level) {
                    $auth_level = escape($pape_resp->nist_auth_level);
                    $success .= "<p>The NIST auth level returned by the " .
                            "server is: <tt>" . $auth_level . "</tt></p>";
                }
            } else {
                $success .= "<p>No PAPE response was sent by the provider.</p>";
            }
            return true;
        }
    }

}

?>
