<?php

/**
 * Collection of Functions
 */
class ImbaSharedFunctions {

    public static function isValidURL($url) {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }

    public static function getAge($timestamp) {
        $ageOfMsg = time() - $timestamp;
        if ($timestamp == 0) {
            $ageOfMsgReturn = "";
        } elseif ($ageOfMsg < '60') {
            $ageOfMsgReturn = $ageOfMsg . " sec(s)";
        } elseif ($ageOfMsg > '59' && $ageOfMsg < '3600') {
            $ageOfMsg = round(($ageOfMsg / 60), 1);
            $ageOfMsgReturn = $ageOfMsg . " min(s)";
        } elseif ($ageOfMsg >= '3600' && $ageOfMsg < '86400') {
            $ageOfMsg = round(($ageOfMsg / 3600), 1);
            $ageOfMsgReturn = $ageOfMsg . " hr(s)";
        } elseif ($ageOfMsg >= '86400' && $ageOfMsg < '604800') {
            $ageOfMsg = round(($ageOfMsg / 86400), 1);
            $ageOfMsgReturn = $ageOfMsg . " day(s)";
        } elseif ($ageOfMsg >= '604800' && $ageOfMsg < '31449600') {
            $ageOfMsg = round(($ageOfMsg / 604800), 1);
            $ageOfMsgReturn = $ageOfMsg . " week(s)";
        } else {
            $ageOfMsg = round(($ageOfMsg / 31449600), 1);
            $ageOfMsgReturn = $ageOfMsg . " year(s)";
        }
        return $ageOfMsgReturn;
    }

    public static function getNiceAge($timestamp) {
        $ageOfMsg = time() - $timestamp;
        if ($timestamp == 0) {
            $ageOfMsgReturn = "Noch nie";
        } elseif ($ageOfMsg < '60') {
            $ageOfMsgReturn = "Vor " . $ageOfMsg . " Sekunden";
        } elseif ($ageOfMsg < '3600') {
            $ageOfMsg = round(($ageOfMsg / 60), 1);
            $ageOfMsgReturn = "Vor " . $ageOfMsg . " Minuten";
        } elseif ($timestamp > strtotime(date('n') . '/' . date('j') . '/' . date('Y'))) {
            $ageOfMsgReturn = strftime("Heute um %H:%M Uhr", $timestamp);
        } elseif ($timestamp > strtotime(date('m/d/y', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))) {
            $ageOfMsgReturn = strftime("Gestern um %H:%M Uhr", $timestamp);
        } elseif ($ageOfMsg <= '604800') {
            $ageOfMsgReturn = strftime("Letzten %A", $timestamp);
        } elseif ($timestamp > strtotime('1/1/' . date('Y'))) {
            $ageOfMsgReturn = strftime("Am %d. %B", $timestamp);
        } else {
            $ageOfMsgReturn = strftime("Am %d. %b. %Y", $timestamp);
        }
        return $ageOfMsgReturn;
    }

    public static function generateHash() {
        $result = "";
        $charPool = '0123456789abcdefghijklmnopqrstuvwxyz';
        for ($p = 0; $p < 15; $p++)
            $result .= $charPool[mt_rand(0, strlen($charPool) - 1)];
        return sha1(md5(sha1($result)));
    }

    public static function genTime($timestamp) {
        return htmlentities(strftime("%e. %B %Y, %H:%M:%S", $timestamp));
    }

    public static function getIP() {
        $ip;

        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "UNKNOWN";

        return $ip;
    }

    public static function makeClickableURL($url) {
        $in = array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si', '`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
        $out = array('<a href="$1" rel="nofollow" target="new">$1</a> ', '<a href="http://$1" rel="nofollow" target="new">$1</a>');
        return preg_replace($in, $out, $url);
    }

    /**
     * Returns a ready to use smarty object
     */
    public static function newSmarty() {
        require_once('Libs/smarty/libs/Smarty.class.php');

        $smarty = new Smarty();

        /**
         * Set smarty dirs
         */
        $smarty->setTemplateDir('Templates');
        $smarty->setCompileDir('Libs/smarty/templates_c');
        $smarty->setCacheDir('Libs/smarty/cache');
        //$smarty->caching = 0;
        $smarty->setConfigDir('Libs/smarty/configs');
        return $smarty;
    }

    /**
     *
     * get Scheme
     */
    public function getScheme() {
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
    public function getReturnTo($hash = false) {
        // this sould be like that, if the webserver would be set up correctly
        // return sprintf("%s://%s:%s%s/?authDone=true", $this->getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], dirname($_SERVER['PHP_SELF']));
        if (($_SERVER['HTTP_REFERER'] == ImbaSharedFunctions::getTrustRoot()) && (ImbaConstants::$WEB_FORCE_PROXY == false)) {
            $authPath = ImbaConstants::$WEB_AUTH_MAIN_PATH;
            if ($hash != false) {
                $authPath .= "?imbaHash=" . $hash;
            }
        } else {
            $authPath = ImbaConstants::$WEB_AUTH_PROXY_PATH;
            if ($hash != false) {
                $authPath .= "&imbaHash=" . $hash;
            }
        }

        return ImbaSharedFunctions::getScheme() . "://" . str_replace("//", "/", sprintf("%s/%s/%s", $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']), $authPath));
    }

    /**
     *
     * get the trust root
     */
    public function getTrustRoot() {
        // this sould be like that, if the webserver would be set up correctly
        // return sprintf("%s://%s:%s%s/", $this->getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], dirname($_SERVER['PHP_SELF']));
        return ImbaSharedFunctions::getScheme() . "://" . str_replace("//", "/", sprintf("%s/%s/", $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF'])));
    }

    /**
     * 
     * fix the path of images for web display
     */
    public function fixWebPath($file) {
        $returnedPath = ImbaSharedFunctions::getTrustRoot();
        if (substr($returnedPath, -1) != "/") {
            $returnedPath .= "/";
        }
        return $returnedPath . $file;
    }

    /**
     *
     * Returns the Domaind of a given URL
     */
    public function getDomain($url) {
        if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === FALSE) {
            return false;
        }
        /*         * * get the url parts ** */
        $parts = parse_url($url);
        /*         * * return the host domain ** */
        return $parts['scheme'] . '://' . $parts['host'];
    }

    /**
     *
     * function to get the tmp dir
     */
    public function getTmpPath() {
        if (!function_exists('sys_get_temp_dir')) {

            function sys_get_temp_dir() {
                if ($temp = getenv('TMP'))
                    return $temp;
                if ($temp = getenv('TEMP'))
                    return $temp;
                if ($temp = getenv('TMPDIR'))
                    return $temp;
                $temp = tempnam(__FILE__, '');
                if (file_exists($temp)) {
                    unlink($temp);
                    return dirname($temp);
                }
                return null;
            }

        }
        return realpath(sys_get_temp_dir());
    }

    /**
     *
     * Function to parse a curl cookie file
     */
    public function curlParseCookiefile($file) {
        $aCookies = array();
        $aLines = file($file);
        foreach ($aLines as $line) {
            if ('#' == $line{0})
                continue;
            $arr = explode("\t", $line);
            if (isset($arr[5]) && isset($arr[6]))
                $aCookies[$arr[5]] = $arr[6];
        }

        return $aCookies;
    }

    /**
     * Function for temporary log writing (debug)
     */
    public static function writeToLog($message) {
        $myFile = "Logs/ImbaLog.log";
        if ($fh = fopen($myFile, 'a+')) {
            $stringData = date("Y-d-m H:i:s") . " (" . ImbaSharedFunctions::getIP() . "): " . $message . "\n";
            fwrite($fh, $stringData);
            fclose($fh);
        }
    }

    /**
     * Function for the proxy to log unusual requests and stuff like that ... yo!
     */
    public static function writeProxyLog($message) {
        $myFile = "Logs/ImbaProxyLog.log";
        if ($fh = fopen($myFile, 'a+')) {
            $stringData = "-------------------------------------------------------------------------------\n";
            $stringData .= date("Y-d-m H:i:s") . " (" . ImbaSharedFunctions::getIP() . "):\n";
            $stringData .= $message;
            $stringData .= "-------------------------------------------------------------------------------\n";
            fwrite($fh, $stringData);
            fclose($fh);
        }
    }

    /**
     * Function for creating random strings
     */
    function getRandomString($length = 8) {
        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }

    /* import from functions.inc.php ! BIG
     *
      function setCookies () {
      #set smf cookie
      $GLOBALS[html] .= "<b>checking for smf user:</b><br />";
      $sql = mysql_query("SELECT id_member,member_name,passwd,password_salt,email_address FROM ".
      $GLOBALS[cfg][usernametable]." WHERE openid_uri='".$_SESSION[openid_identifier]."';");
      while ($row = mysql_fetch_array($sql)) {
      $data = array($row[id_member], sha1($row[passwd] . $row[password_salt]), (time() + 3600), 0);
      setcookie('alpCookie',serialize($data),(time() + 3600),$GLOBALS[cfg][cookiepath],$GLOBALS[cfg][cookiedomain]);
      $nametransfer = $row[member_name];
      $tmpemailtransfer = $row[email_address];
      $GLOBALS[html] .= "- ".$row[member_name]." found!<br /><b>= setting cookie</b><br />";
      $_SESSION[sites][smf][$row[member_name]] = "cookie";
      $_SESSION[myname] = $row[member_name];
      if ($row[member_name] == "") return 0;
      }
      if (empty($_SESSION[sites][smf]))
      $_SESSION[sites][smf] = -1;
      $GLOBALS[html] .= "<br />";

      #called after openid verification, searches the users in all registered databases ($GLOBALS[cfg][sites])
      function checkSites () {
      foreach ($GLOBALS[cfg][sites] as $mysite) {
      $GLOBALS[html] .= "<b>checking ".$mysite[name]." for ".$_SESSION[openid_identifier]."</b>:<br />";

      if ((! empty($mysite[ltable])) and (! empty($mysite[lafield])) and (! empty($mysite[lqfield]))) {
      $GLOBALS[html] .= "- looking for ".$_SESSION[openid_identifier]." in ".$mysite[ltable]." -&gt; ".$mysite[lqfield]."<br />";
      $lsql = mysql_query("SELECT ".$mysite[lafield]." FROM ".$mysite[ltable]." WHERE ".$mysite[lqfield]."='".$_SESSION[openid_identifier]."';");
      while ($lrow = mysql_fetch_array($lsql)) {
      $mytmpname = $lrow[$mysite[lafield]];
      }
      } else {
      $mytmpname = $_SESSION[openid_identifier];
      }

      $GLOBALS[html] .= "- looking for ".$mytmpname." in ".$mysite[utable]." -&gt; ".$mysite[uqfield]."<br />";
      $usql = mysql_query("SELECT ".$mysite[uafield]." FROM ".$mysite[utable]." WHERE ".$mysite[uqfield]."='".$mytmpname."';");
      while ($urow = mysql_fetch_array($usql)) {
      $myresult = $urow[$mysite[uafield]];
      }

      if (empty($myresult)) {
      $GLOBALS[html] .= "<b>= notfound</b>";
      $_SESSION[sites][$mysite[name]] = -1;
      } else {
      $GLOBALS[html] .= "<b>= found: ".$myresult."</b>";
      $_SESSION[sites][$mysite[name]][$myresult] = "form";
      #			$_SESSION[sites][$mysite[name]][form] = 2;
      }
      $GLOBALS[html] .= "<br /><br />";

      }
      }

      #daemon function
      function getXmppUsers() {
      #load users from db (oom_.._xmpp)
      $count = 0;
      $sql = mysql_query("SELECT openid,xmpp FROM ".$GLOBALS[cfg][msg][xmpptable]." WHERE 1;");
      while ($row = mysql_fetch_array($sql)) {
      if (! empty($GLOBALS[users][byuri][$row[openid]][name])) {
      $GLOBALS[users][byxmpp][$row[xmpp]] = $row[openid];
      $GLOBALS[users][byuri][$row[openid]][xmpp] = $row[xmpp];
      $GLOBALS[users][bylowxmpp][strtolower($row[xmpp])] = $row[openid];

      $count++;
      }
      }
      $GLOBALS[users][count][xmpp] = $count;
      }

      function checkSession () {
      $bool = 1;
      $sql = mysql_query("SELECT hash FROM ".$GLOBALS[cfg][sessiontable]." WHERE openid='".$_SESSION[openid_identifier]."';");
      while ($row = mysql_fetch_array($sql))
      if ($_SESSION[hash] == $row[hash])
      $bool = 0;

      if ($bool)	$GLOBALS[forcelogout] = 1;
      else				$GLOBALS[forcelogout] = 0;
      }

      function createSession () {
      $_SESSION[hash] = generateHash(); $bool = 0;
      $sqlq = mysql_query("SELECT hash FROM ".$GLOBALS[cfg][sessiontable]." WHERE openid='".$_SESSION[openid_identifier]."';");
      while ($rowq = mysql_fetch_array($sqlq))
      $bool = 1;

      if ($bool)
      $sql = mysql_query("UPDATE ".$GLOBALS[cfg][sessiontable]." SET hash='".$_SESSION[hash]."' WHERE openid='".$_SESSION[openid_identifier]."';");
      else
      $sql = mysql_query("INSERT INTO ".$GLOBALS[cfg][sessiontable]." (openid,hash) VALUES ('".$_SESSION[openid_identifier]."', '".$_SESSION[hash]."');");

      $_SESSION[phpdebug] = $GLOBALS[debug];
      $_SESSION[jsdebug] = 0;
      $_SESSION[jsversion] = $GLOBALS[version];
      $_SESSION[reqdebug] = 0;
      $_SESSION[freshlogin] = 0;
      }

      function encodeme($me) {
      return mysql_real_escape_string(htmlspecialchars(str_replace('&', '&amp;', trim($me))));
      }

      function xmppencode($me) {
      return utf8_encode(htmlspecialchars($me));
      }

      ################## OpenID Profile Functions ##################

      TODO: hier werden die profile für die verschienen tools angepasst (ask oxi)
      function applyProfile ($myuser, $myprofile) {
      #apply profile function
      fetchUsers();
      $GLOBALS[html] .= "<h3>=&gt; Changing User ".$myuser." to ".
      $GLOBALS[cfg][profile][$myprofile][name]."</h3>";

      #wordpress
      if (! empty($GLOBALS[users][byuri][$myuser][wordpress])) {
      $GLOBALS[html] .= "- modifying wordpress user ".$GLOBALS[users][byuri][$myuser][wordpress]." :)<br />";
      $sql = mysql_query("UPDATE wp_usermeta SET meta_key='".$GLOBALS[cfg][profile][$myprofile][wordpress].
      "'WHERE user_id='".$GLOBALS[users][byuri][$myuser][wordpress]."' AND meta_key='wp_user_level';");
      }

      #smf
      if (! empty($GLOBALS[users][byuri][$myuser][smf])) {
      $GLOBALS[html] .= "- modifying smf user ".$GLOBALS[users][byuri][$myuser][smf]." :)<br />";
      $sql = mysql_query("UPDATE smf_members SET id_group='".$GLOBALS[cfg][profile][$myprofile][smf].
      "', lngfile='german-utf8', additional_groups='' WHERE id_member='".$GLOBALS[users][byuri][$myuser][smf]."';");
      }

      #phpraider
      if (! empty($GLOBALS[users][byuri][$myuser][phpraider])) {
      $GLOBALS[html] .= "- modifying phpraider user ".$GLOBALS[users][byuri][$myuser][phpraider]." :)<br />";
      $sql = mysql_query("UPDATE phpraider_profile SET group_id='".$GLOBALS[cfg][profile][$myprofile][phpraider].
      "' WHERE profile_id='".$GLOBALS[users][byuri][$myuser][phpraider]."';");
      }

      # set openid profile
      $GLOBALS[html] .= "- modifying openid user :)<br />";
      $sql = mysql_query("UPDATE ".$GLOBALS[cfg][userprofiletable]." SET role='".$myprofile."' WHERE openid='".$myuser."';");

      $GLOBALS[html] .= "<h3>=&gt; Changes done</h3>";
      }


      ################## WOW Armory Functions ##################

      function fetchArmoryXML ($type, $target) {
      #	if (true) {
      if ($GLOBALS[armorydowntimestamp] > (time() - $GLOBALS[armorydownwait])) {
      $GLOBALS[armorydown] = 1;
      return '<'.'?'.'xml version="1.0" encoding="ISO-8859-1"?'.'>'.
      '<page globalSearch="1" lang="en_us"><errorhtml type="503"/></page>';
      } else {
      $BASEURL = "http://eu.wowarmory.com/";
      if ($type == "i")
      $URL = $BASEURL."item-info.xml?i=".$target;
      elseif ($type == "n")
      $URL = $BASEURL."character-sheet.xml?r=".$GLOBALS[realm]."&n=".$target;
      else return 0;
      $URL .= "&rhtml=ni";
      $useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; de-DE; rv:1.6) Gecko/20040206 Firefox/1.0.1";
      ini_set('user_agent',$useragent);
      $curl = curl_init();
      curl_setopt ($curl, CURLOPT_URL, $URL);
      curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept-Language: de-de, de;"));
      $load = curl_exec($curl);
      curl_close($curl);
      if (strpos($load, '<errorhtml type="503"/>')) {
      $GLOBALS[armorycharupdatecount] = $GLOBALS[armorycharmaxupdate] + 1;
      $GLOBALS[armorydown] = 1;
      $GLOBALS[armorydowntimestamp] = time();
      sysmsg ("WOW Armory down.", 3);
      $sql = mysql_query("UPDATE ".$GLOBALS[cfg][settingstable]." SET value='".
      $GLOBALS[armorydowntimestamp]."' WHERE name='armorydowntimestamp';");
      }
      return $load;
      }
      }

      function loadArmoryNames () {
      #load names into memory $GLOBALS[armorynames]
      #id, category, iid, name
      if (! $GLOBALS[armorynames][init]) {
      $sql = mysql_query("SELECT category,iid,name FROM ".$GLOBALS[cfg][armory][names]." WHERE 1;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[armorynames][$row[category]][$row[iid]] = $row[name];
      $GLOBALS[armorynames][init] = true;
      }
      }
      }

      function showArmoryName ($category, $id) {
      loadArmoryNames();
      #show name, if nonexistant, value
      $ret = $GLOBALS[armorynames][$category][$id];
      if (empty($ret))
      $ret = $id;
      return $ret;
      }

      function genArmoryIlvl ($mychar) {
      if (count($mychar->characterInfo->characterTab->items->item)) {
      $count = 0; $total = 0;
      foreach ($mychar->characterInfo->characterTab->items->item as $myitem) {
      if (($myitem->attributes()->slot != 3) AND
      ($myitem->attributes()->slot != 18) AND
      ($myitem->attributes()->slot != -1)) {
      $count++;
      $total += (integer) $myitem->attributes()->level;
      }
      }
      return round($total/$count);
      } else return 0;
      }

      function genArmorySkills ($mychar) {
      if (count($mychar->characterInfo->characterTab->professions->skill)) {
      $tmp = array();
      foreach ($mychar->characterInfo->characterTab->professions->skill as $myskill) {
      array_push($tmp, array((integer) $myskill->attributes()->id => (integer) $myskill->attributes()->value));
      }
      return $tmp;
      } else return null;
      }

      function genArmoryAchievments ($mychar) {
      if (count($mychar->characterInfo->summary->category)) {
      $tmp = array();
      foreach ($mychar->characterInfo->summary->category as $myachievment) {
      array_push($tmp, array((integer) $myachievment->attributes()->id => (integer) $myachievment->c->attributes()->earnedPoints));
      }
      return $tmp;
      } else return null;
      }


      function fetchArmoryItem ($itemid) {

      # id, icon, level, quality, type, name, timestamp
      $myitem = "";
      unset($char);
      $sql = mysql_query("SELECT id, icon, level, quality, type, name, timestamp FROM ".
      $GLOBALS[cfg][armory][itemcachetable]." WHERE id='".$itemid."';");
      $myitem[level] = null;
      while ($row = mysql_fetch_array($sql)) {
      $myitem[timestamp]	= $row[timestamp];
      $myitem[name]				= $row[name];
      $myitem[level]			= $row[level];
      $myitem[id]					= $row[id];
      $myitem[quality]		= $row[quality];
      $myitem[type]				= $row[type];
      $myitem[icon]				= $row[icon];
      }
      return $myitem;
      }
     */
}

?>
