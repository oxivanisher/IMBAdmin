<?php

/**
 * Loading required libs
 */
require_once "Libs/lightopenid/openid.php";

require_once "Controller/ImbaUserContext.php";
require_once "Controller/ImbaManagerDatabase.php";
require_once "Controller/ImbaManagerUser.php";
require_once "Model/ImbaUser.php";
require_once "ImbaConstants.php";

/**
 * TMP Space
 */
/*

  Consumer key
  8eBPNeahbNk1VLGopAcIxw
  Consumer secret
  bJpskxvfKSxFuPxThOXDMu84sxaHhc6fAIbBbXmto
  Request token URL
  http://twitter.com/oauth/request_token
  Access token URL
  http://twitter.com/oauth/access_token
  Authorize URL
  http://twitter.com/oauth/authorize *We support hmac-sha1 signatures. We do not support the plaintext signature method.


  $consumerKey='xxxxxxxxxxxxxxxxxx';
  $consumerSecret='xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

  $config=array(
  'callbackUrl'=>'http://yourdomain.com/callback.php',
  'siteUrl' => 'http://twitter.com/oauth',
  'consumerKey'=>$consumerKey,
  'consumerSecret'=>$consumerSecret
  );

  // creat oauth object
  $oauth=new Zend_Oauth_Consumer($config);
  // get request token
  try{
  $request_token = $oauth->getRequestToken();
  }
  catch(Exception $e)
  {
  echo 'Error: '.$e->getMessage();
  exit (1);
  }
  // store request token in session
  $_SESSION['request_token']=serialize($request_token);

  // explode request token to extract oauth token
  $exploded_request_token=explode('=',str_replace('&','=',$request_token));
  // get oauth token from exploded request token
  $oauth_token=$exploded_request_token[1];
  // show sign in with twitter button
  echo "<a href='http://twitter.com/oauth/authorize?oauth_token={$oauth_token}'><img src='sign-in-with-twitter-button.png' alt='Twitter button' /></a>";

 */

/**
 * END TMP Space
 */

/**
 * Description of ImbaManagerOauth
 * - Handles OAuth verification, authentifications
 */
class ImbaManagerOauth {

    private static $oauthConfig = null;
    private static $oauth = null;
    private static $htmlOut = null;
    private static $accessToken = null;

    /**
     * ctor
     */
    private function __construct() {
        $this->oauthConfig = array(
            'callbackUrl' => ImbaSharedFunctions::getReturnTo(),
            'siteUrl' => 'http://twitter.com/oauth',
            'consumerKey' => ImbaConfig::$TWITTER_CONSUMER_KEY,
            'consumerSecret' => ImbaConfig::$TWITTER_CONSUMER_SECRET
        );
        // creat oauth object
        $this->oauth = new Zend_Oauth_Consumer($this->oauthConfig);

        // get request token
        try {
            // store request token in session
            $_SESSION['request_token'] = serialize($this->oauth->getRequestToken());

            // explode request token to extract oauth token
            $exploded_request_token = explode('=', str_replace('&', '=', $request_token));
            // get oauth token from exploded request token
            $oauth_token = $exploded_request_token[1];
            // show sign in with twitter button
            $this->htmlOut = "<a href='http://twitter.com/oauth/authorize?oauth_token={$oauth_token}'><img src='sign-in-with-twitter-button.png' alt='Twitter button' /></a>";
        } catch (Exception $e) {
            $this->htmlOut = 'Error: ' . $e->getMessage();
        }
    }

    /**
     * OpenAuth return html
     */
    public function getHtmlOut() {
        return $this->htmlOut;
    }

    /**
     * OpenAuth Auth
     */
    public function oauthAuth() {
        if (isset($_GET['oauth_token']) && isset($_SESSION['request_token'])) {
            try {
                $this->accessToken = $this->oauth->getAccessToken($_GET, unserialize($_SESSION['request_token']));
            } catch (Exception $e) {
                throw new Exception('oauthAuth Error: ' . $e->getMessage());
            }

            $_SESSION['access_token'] = serialize($this->accessToken);
            $_SESSION['request_token'] = null;

            /**
             * The user is now logged in. save the token $this->accessToken
             */
            return $this->accessToken;
        } elseif (!empty($_GET['denied'])) {
            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_CANCEL);
        } else {
            throw new Exception('oauthAuth Error: Invalid callback request. Oops. Sorry.');
        }
    }

}

?>