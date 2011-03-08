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
            $ageOfMsgReturn = "noch nie";
        } elseif ($ageOfMsg < '60') {
            $ageOfMsgReturn = "vor " . $ageOfMsg . " Sek";
        } elseif ($ageOfMsg < '3600') {
            $ageOfMsg = round(($ageOfMsg / 60), 1);
            $ageOfMsgReturn = "vor " . $ageOfMsg . " Min";
        } elseif ($ageOfMsg <= '86400') {
            $ageOfMsgReturn = strftime("um %H:%M Uhr", $timestamp);
        } elseif ($ageOfMsg <= '604800') {
            $ageOfMsgReturn = strftime("am %A", $timestamp);
        } elseif ($ageOfMsg <= '2419200') {
            $ageOfMsgReturn = strftime("im %B", $timestamp);
        } else {
            $ageOfMsg = round(($ageOfMsg / 31449600), 1);
            $ageOfMsgReturn = strftime("anno %Y", $timestamp);
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
        return strftime("%e. %B %Y, %H:%M:%S", $timestamp);
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
        $out = array('<a href="$1" rel="nofollow" target="new" class="ssoMessageLink">$1</a> ', '<a href="http://$1" rel="nofollow" target="new" class="ssoMessageLink">$1</a>');
        return preg_replace($in, $out, $url);
    }

    // TODO: irgendwie ein logging einbauen
    public static function sysmsg($msg, $lvl =2, $user ="", $subject ="") {
        switch ($lvl) {
            case 0 :
                $rmsg = "ERROR: ";
                break;

            case 1 :
                $rmsg = "WARNING: ";
                break;

            case 2 :
                $rmsg = "INFO: ";
                break;

            default :
                $rmsg = "UNSET: ";
        }

        if ($GLOBALS[bot]) {
            $thash = $subject;
            $tmodule = "xmpp_daemon";
            $tuser = $user;
            $tip = "XMPP";
        } else {
            $thash = $_SESSION[hash];
            $tmodule = $_POST[module];
            $tuser = $_SESSION[openid_identifier];
            $tip = getIP();
        }

        if ($lvl <= $GLOBALS[sysmsglvl])
            $sqlsysmsg = mysql_query("INSERT INTO " . $GLOBALS[cfg][systemmsgsdb] . " (timestamp,user,ip,module,session,msg,lvl) VALUES " . "('" . time() . "', '" . $tuser . "', '" . $tip . "', '" . $tmodule . "', '" . $thash . "', '" . $msg . "', '" . $lvl . "');");

        if ($lvl < 2)
            $GLOBALS[html] .= "<b>" . $msg . "</b>";

        if ($lvl == 0)
            alert($rmsg . $msg, $tuser);
    }

    // TODO: irgendwie ein alerting einbauen
    public static function alert($msg, $from) {
        $alertsql = mysql_query("SELECT openid FROM " . $GLOBALS[cfg][admintablename] . " WHERE dev='1';");
        while ($alertrow = mysql_fetch_array($alertsql)) {
            $sql = mysql_query("INSERT INTO " . $GLOBALS[cfg][msg][msgtable] . " (sender,receiver,timestamp,subject,message,new,xmpp) VALUES " . "('" . $from . "', '" . $alertrow[openid] . "', '" . time() . "', 'SYSTEM INFORMATION', '" . $msg . "', 1, 1);");
        }
    }

    // TODO: irgendwie ein user alerting einbauen
    public static function informUsers($msg, $role) {
        $alertsql = mysql_query("SELECT openid FROM " . $GLOBALS[cfg][userprofiletable] . " WHERE role>='" . $role . "';");
        while ($alertrow = mysql_fetch_array($alertsql)) {
            $sql = mysql_query("INSERT INTO " . $GLOBALS[cfg][msg][msgtable] . " (sender,receiver,timestamp,subject,message,new,xmpp) VALUES " . "('" . $_SESSION[openid_identifier] . "', '" . $alertrow[openid] . "', '" . time() . "', 'SYSTEM MESSAGE', '" . $msg . "', 1, 1);");
        }
    }

    public static function sendMail($target, $subject, $message) {
        if (substr($target, 0, 4) == "http") {
            // FIXME: ich bin hässlich hard-gecodet ohne DB manager!
            $sql = "SELECT email FROM " . $GLOBALS[cfg][userprofiletable] . " WHERE openid='" . $target . "';";
            $sqlr = mysql_query($sql);
            while ($row = mysql_fetch_array($sqlr))
                $targetaddr = $row[email];
        } else {
            $targetaddr = $target;
        }
        #check for correct email addr
        if (filter_var($targetaddr, FILTER_VALIDATE_EMAIL)) {
            if ($GLOBALS[adminemailname]) {
                $sender = $GLOBALS[adminemailname];
            } else {
                $sender = "IMBA Admin @ " . $_SERVER[SERVER_NAME];
            }

            // FIXME: 8ung! hier fehlt eine konstante für die admin email adresse
            $header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8' . "\n" . 'From: ' . $sender . ' <' . $GLOBALS[adminemail] . ">\n";
            // Make sure there are no bare linefeeds in the headers
            $header = preg_replace('#(?<!\r)\n#si', "\r\n", $header);
            // Fix any bare linefeeds in the message to make it RFC821 Compliant.
            $message = preg_replace("#(?<!\r)\n#si", "\r\n", $message);

            mail($targetaddr, $subject, $message, $header);
            return $targetaddr;
        } else {
            return false;
        }
    }

    public static function genAjaxWebLink($action, $tabId, $module) {
        return sprintf("?action=%s&tabId=%s&module=%s", $action, $tabId, $module);
    }

    public static function newSmarty() {
        require_once('Libs/smarty/libs/Smarty.class.php');
        
        $smarty = new Smarty();

        /**
         * Set smarty dirs
         */
        $smarty->setTemplateDir('Templates');
        $smarty->setCompileDir('Libs/smarty/templates_c');
        $smarty->setCacheDir('Libs/smarty/cache');
        $smarty->setConfigDir('Libs/smarty/configs');
        
        return $smarty;
    }

    public static function killCookies() {
        // unset smf cookie
        //setcookie('alpCookie', serialize(array(0, '', 0)), time() - 3600, $GLOBALS[cfg][cookiepath], $GLOBALS[cfg][cookiedomain]);
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

      #set eqdkp cookie
      $mybool = 1;
      $GLOBALS[html] .= "<b>checking for eqdkp user:</b><br />";
      $sql = mysql_query("SELECT user_password,user_id,username FROM eqdkp_users WHERE username='".$nametransfer."';");
      while ($row = mysql_fetch_array($sql)) {
      $mybool = 0;
      $data[auto_login_id] = $row[user_password];
      $data[user_id] = $row[user_id];
      setcookie('eqdkp_data',serialize($data),(time() + 3600),$GLOBALS[cfg][cookiepath],'');
      $GLOBALS[html] .= "- ".$row[username]." found!<br /><b>= setting cookie</b><br />";
      $_SESSION[sites][eqdkp][$row[username]] = "cookie";
      }

      if (($mybool) AND (! empty($nametransfer))) {
      $sql2 = mysql_query("INSERT INTO eqdkp_users (username, user_email, user_password) VALUES ('".$nametransfer.
      "', '".$tmpemailtransfer."', '".md5(rand())."');");

      $sql = mysql_query("SELECT user_password,user_id,username FROM eqdkp_users WHERE username='".strtolower($nametransfer)."';");
      while ($row = mysql_fetch_array($sql)) {
      $data[auto_login_id] = $row[user_password];
      $data[user_id] = $row[user_id];
      setcookie('eqdkp_data',serialize($data),(time() + 3600),$GLOBALS[cfg][cookiepath],'');
      $GLOBALS[html] .= "- ".$row[username]." created!<br /><b>= setting cookie</b><br />";
      $_SESSION[sites][eqdkp][$row[username]] = "cookie";
      }
      }

      if (empty($_SESSION[sites][eqdkp]))
      $_SESSION[sites][eqdkp] = -1;
      $GLOBALS[html] .= "<br />";
      }

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

      #fetch users function
      function fetchUsers () {
      #cleanup
      unset ($GLOBALS[users]);
      $count = 0;
      #fetching oom openid profile informations
      $sql = mysql_query("SELECT nickname,openid,role,armorychars FROM ".$GLOBALS[cfg][userprofiletable]." WHERE 1 ORDER BY nickname;");
      while ($row = mysql_fetch_array($sql)) {
      if (! empty($row[openid])) {
      if ((! $row[role]) AND (! $_SESSION[isadmin]))
      continue;

      $GLOBALS[users][byname][strtolower($row[nickname])] = $row[openid];
      $GLOBALS[users][byuri][$row[openid]][name] = $row[nickname];
      $GLOBALS[users][byuri][$row[openid]][uri] = $row[openid];
      $GLOBALS[users][byuri][$row[openid]][role] = $row[role];
      $GLOBALS[users][byuri][$row[openid]][armorychars] = unserialize($row[armorychars]);
      $count++;
      }
      }
      $GLOBALS[users][count][all] = $count;

      #get multigaming data
      $sql = mysql_query("SELECT * FROM ".$GLOBALS[cfg][mg][namestable]." WHERE 1 ORDER BY openid;");
      $last = "";
      while ($row = mysql_fetch_array($sql)) {
      if (empty($GLOBALS[users][byuri][$row[openid]]))
      continue;
      if ($row[openid] != $last)
      $GLOBALS[users][byuri][$row[openid]][multigaming] = array();
      array_push($GLOBALS[users][byuri][$row[openid]][multigaming], array($row[gameid] => $row[name]));
      $last = $row[openid];
      }

      #get smf id's
      $count = 0;
      $sql = mysql_query("SELECT openid_uri,member_name,id_member FROM ".$GLOBALS[cfg][usernametable]." WHERE 1;");
      while ($row = mysql_fetch_array($sql))
      if (! empty($row[openid_uri])) {
      if (! empty($GLOBALS[users][byuri][$row[openid_uri]])) {
      $GLOBALS[users][byuri][$row[openid_uri]][smf] = $row[id_member];
      $count++;
      }
      }
      $GLOBALS[users][count][smf] = $count;

      getXmppUsers();

      #fetching users from openid lastonline db
      $sqls = mysql_query("SELECT openid,timestamp,status,xmppstatus,chatid,chatsubscr FROM ".$GLOBALS[cfg][lastonlinedb]." WHERE 1;");
      while ($rows = mysql_fetch_array($sqls)) {
      if (! empty($GLOBALS[users][byuri][$rows[openid]][name])) {
      $GLOBALS[users][byuri][$rows[openid]][online] = $rows[timestamp];
      $GLOBALS[users][byuri][$rows[openid]][status] = $rows[status];
      $GLOBALS[users][byuri][$rows[openid]][xmppstatus] = $rows[xmppstatus];
      $GLOBALS[users][byuri][$rows[openid]][chat] = $rows[chatid];
      $GLOBALS[users][bychat][$rows[chatid]] = $GLOBALS[users][byuri][$rows[openid]][name];
      if ($_SESSION[openid_identifier] == $rows[openid]) {
      $GLOBALS[chat][subscr] = unserialize($rows[chatsubscr]);
      }
      }
      }

      #eqdkp
      $count = 0;
      $sql = mysql_query("SELECT user_id,username FROM eqdkp_users WHERE 1;"); #username='".strtolower($myurl[name]."';"));
      while ($row = mysql_fetch_array($sql)) {
      foreach ($GLOBALS[users][byuri] as $myurl) {
      if (strtolower($myurl[name]) == strtolower($row[username])) {
      if (! empty($GLOBALS[users][byuri][$myurl[uri]][name])) {
      $GLOBALS[users][byuri][$myurl[uri]][eqdkp] = $row[user_id];
      $count++;
      }
      }
      }
      }
      $GLOBALS[users][count][eqdkp] = $count;

      #mediawiki
      $count = 0;
      $sql = mysql_query("SELECT uoi_user,uoi_openid FROM WIKI_user_openid WHERE 1;");
      while ($row = mysql_fetch_array($sql))
      if (! empty($row[uoi_user])) {
      if (! empty($GLOBALS[users][byuri][$row[uoi_openid]][name])) {
      $GLOBALS[users][byuri][$row[uoi_openid]][mediawiki] = $row[uoi_user];
      $count++;
      }
      }
      $GLOBALS[users][count][mediawiki] = $count;

      #wordpress
      $count = 0;
      $sql = mysql_query("SELECT user_id,url FROM wp_openid_identities WHERE 1;");
      while ($row = mysql_fetch_array($sql))
      if (! empty($row[url])) {
      if (! empty($GLOBALS[users][byuri][$row[url]][name])) {
      $GLOBALS[users][byuri][$row[url]][wordpress] = $row[user_id];
      $count++;
      }
      }
      $GLOBALS[users][count][wordpress] = $count;

      #phpraider
      $count = 0;
      $sql = mysql_query("SELECT profile_id,username FROM phpraider_profile WHERE 1;");
      while ($row = mysql_fetch_array($sql)) {
      foreach ($GLOBALS[users][byuri] as $myurl) {
      if (strtolower($myurl[name]) == strtolower($row[username])) {
      if (! empty($GLOBALS[users][byuri][$myurl[uri]][name])) {
      $GLOBALS[users][byuri][$myurl[uri]][phpraider] = $row[profile_id];
      $count++;
      }
      }
      }
      }
      $GLOBALS[users][count][phpraider] = $count;

      #fetch profiles
      $count = 0;
      $sql = mysql_query("SELECT * FROM ".$GLOBALS[cfg][profiletable]." WHERE 1;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[cfg][profile][$row[role]][handle] = $row[handle];
      $GLOBALS[cfg][profile][$row[role]][id] = $row[id];
      $GLOBALS[cfg][profile][$row[role]][name] = $row[name];
      $GLOBALS[cfg][profile][$row[role]][smf] = $row[smf];
      $GLOBALS[cfg][profile][$row[role]][eqdkp] = $row[eqdkp];
      $GLOBALS[cfg][profile][$row[role]][wordpress] = $row[wordpress];
      $GLOBALS[cfg][profile][$row[role]][icon] = $row[icon];
      $GLOBALS[cfg][profile][$row[role]][phpraider] = $row[phpraider];

      }
      $GLOBALS[cfg][count][profile] = $count;
      }

      #draw users dropdown
      function drawUsersDropdown($selected = FALSE) {
      $tmphtml = "";
      $tmphtml .= "<select name='user'>";
      $tmphtml .= "<option value=''>Benutzer Auswahl</option>";

      ksort($GLOBALS[users][byname]);
      foreach ($GLOBALS[users][byname] as $tmpname => $mytmpurl)
      if (! empty($GLOBALS[users][byuri][$mytmpurl])) {
      if ($selected == $GLOBALS[users][byuri][$mytmpurl][uri]) $stmp = " selected";
      else $stmp = "";
      $tmphtml .= "<option value='".$GLOBALS[users][byuri][$mytmpurl][uri]."'".
      $stmp.">".$GLOBALS[users][byuri][$mytmpurl][name]."</option>";
      }
      $tmphtml .= "</select>";
      return $tmphtml;
      }

      #draw profile dropdown
      function drawProfileDropdown() {
      $tmphtml = "<select name='profile'>";
      $tmphtml .= "<option value=''>Profil Auswahl</option>";
      foreach ($GLOBALS[cfg][profile] as $myname => $myprofile) {
      if (($myname < $GLOBALS[users][byuri][$_SESSION[openid_identifier]][role]) OR ($GLOBALS[users][byuri][$_SESSION[openid_identifier]][role] == 9))
      $tmphtml .= "<option value='".$myname."'>".$myprofile[name]."</option>";
      }
      $tmphtml .= "</select>";
      return $tmphtml;
      }

      #draw smf users without openid dropdown
      function drawSmfUsersDropdown() {
      $tmphtml = "";
      $tmphtml .= "<select name='newuser'>";
      $tmphtml .= "<option value=''>Choose User</option>";
      $sqlt = mysql_query("SELECT member_name,id_member FROM ".$GLOBALS[cfg][usernametable]." WHERE openid_uri='' ORDER BY member_name ASC;");
      while ($rowt = mysql_fetch_array($sqlt)) {
      $tmphtml .= "<option value='".$rowt[id_member]."'>".$rowt[member_name]."</option>";
      }
      $tmphtml .= "</select>";
      return $tmphtml;
      }

      function genUserLink($user) {
      #	return "<a href='?module=messaging&myjob=composemessage&user=".$GLOBALS[users][byuri][$user][uri].
      #					"'>".$GLOBALS[users][byuri][$user][name]."</a>";
      return "<a href='?module=userprofile&user=".$GLOBALS[users][byuri][$user][uri].
      "'>".$GLOBALS[users][byuri][$user][name]."</a>";
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

      function getOnlineUsers () {
      #last online implementation (also called by ajax dynamic update for messaging overview)
      #unset users array (because this function could be called multiple times)
      unset ($GLOBALS[aryNames], $GLOBALS[aryOpenID], $GLOBALS[aryStatus], $GLOBALS[aryTimes], $GLOBALS[aryNewMessage]);
      $GLOBALS[ajaxuserreturnname] = array();
      $GLOBALS[ajaxuserreturnopenid] = array();
      $GLOBALS[ajaxuserreturnstatus] = array();
      $GLOBALS[ajaxuserreturntimes] = array();
      $GLOBALS[ajaxuserreturnnewmessage] = array(); $newmsgopenid = array();
      $bool = false; $cnt = 0;
      $ocnt = 0; $ousers = ""; $obool = 1; $otmp = "";
      $icnt = 0; $iusers = ""; $ibool = 1; $itmp = "";
      $fcnt = 0; $fbool = 1;
      $firstremote = 1;

      #create chat channel output.
      foreach (getMyChatChannels() as $mychan) {
      array_push($GLOBALS[ajaxuserreturnname], $mychan[name]);
      array_push($GLOBALS[ajaxuserreturnopenid], $mychan[id]);
      array_push($GLOBALS[ajaxuserreturntimes], $mychan[lastmessage]);
      array_push($GLOBALS[ajaxuserreturnnewmessage], "0");
      array_push($GLOBALS[ajaxuserreturnstatus], "3");
      }

      #get the new messages informations
      $newmsgsql = mysql_query("SELECT receiver,sender FROM ".$GLOBALS[cfg][msg][msgtable]." WHERE new='1';");
      while ($nrow = mysql_fetch_array($newmsgsql))
      array_push($newmsgopenid, array($nrow[receiver], $nrow[sender]));

      #system messages
      foreach ($newmsgopenid as $mymsg) {
      if (($mymsg[0] == $_SESSION[openid_identifier])
      AND (empty($GLOBALS[users][byuri][$mymsg[1]][name]))) {
      array_push($GLOBALS[ajaxuserreturnname], $GLOBALS[adminmsgname]);
      array_push($GLOBALS[ajaxuserreturnopenid], chop($mymsg[1]));
      array_push($GLOBALS[ajaxuserreturntimes], getNiceAge($orow[timestamp]));
      array_push($GLOBALS[ajaxuserreturnnewmessage], 1);
      array_push($GLOBALS[ajaxuserreturnstatus], "1");
      continue;
      }
      }

      #get the users from last online table and go trough them
      $onlinesql = mysql_query("SELECT openid,timestamp,xmppstatus FROM ".$GLOBALS[cfg][lastonlinedb]." WHERE 1 ORDER BY timestamp DESC;");
      while ($orow = mysql_fetch_array($onlinesql)) {
      #catch some eventually problems
      if ($orow[name] == '0') continue;

      #am i this user here?
      if ($orow[openid] == $_SESSION[openid_identifier]) {
      $cnt++;
      $bool = true;
      if ($orow[timestamp] > ( time() - $GLOBALS[cfg][lastonlinetimeout] )) {
      $ocnt++;
      } elseif ($orow[timestamp] > ( time() - $GLOBALS[cfg][lastidletimeout] )) {
      $icnt++;
      } else {
      $fcnt++;
      }
      if ($obool)	$obool = 0;
      else				$otmp = ", ";
      $ousers .= $otmp.$GLOBALS[users][byuri][$orow[openid]][name];
      continue;
      }

      #count new mwssages
      $mcount = 0;
      foreach ($newmsgopenid as $mymsg) {
      if (($mymsg[0] == $_SESSION[openid_identifier]) AND ($mymsg[1]  == $orow[openid]))
      $mcount++;
      }
      #end currend user run if a banned or not correct user (exept system messages)
      if (empty($GLOBALS[users][byuri][$orow[openid]][name])) continue;

      #yes, this is a ok user
      $cnt++;

      array_push($GLOBALS[ajaxuserreturnname], $GLOBALS[users][byuri][$orow[openid]][name]);
      array_push($GLOBALS[ajaxuserreturnopenid], $orow[openid]);
      array_push($GLOBALS[ajaxuserreturntimes], getNiceAge($orow[timestamp]));

      array_push($GLOBALS[ajaxuserreturnnewmessage], $mcount);

      #is the user online?
      if ($orow[timestamp] > ( time() - $GLOBALS[cfg][lastonlinetimeout] )) {
      $ocnt++;
      if ($obool)	$obool = 0;
      else				$otmp = ", ";
      $ousers .= $otmp.$GLOBALS[users][byuri][$orow[openid]][name];
      array_push($GLOBALS[ajaxuserreturnstatus], "1");
      continue;
      }

      #is the user idle?
      if ($orow[timestamp] > ( time() - $GLOBALS[cfg][lastidletimeout] )) {
      $icnt++;
      if ($ibool)	$ibool = 0;
      else				$itmp = ", ";
      $iusers .= $itmp.$GLOBALS[users][byuri][$orow[openid]][name];
      array_push($GLOBALS[ajaxuserreturnstatus], "0");
      continue;

      #or is the user remote online (xmpp jabber daemon)
      } elseif ($orow[xmppstatus]) {
      if ($ibool)	$ibool = 0;
      else				$itmp = ", ";

      if ($firstremote)	{
      $itmp = " | ";
      $firstremote = 0;
      } else $itmp = ", ";

      $iusers .= $itmp.$GLOBALS[users][byuri][$orow[openid]][name];

      array_push($GLOBALS[ajaxuserreturnstatus], "2");
      $icnt++;

      #so, the user is offline then (to infinity and beyond!)
      } else {
      array_push($GLOBALS[ajaxuserreturnstatus], "-1");
      $fcnt++;
      }
      continue;

      #we shall never reach this point
      }

      $GLOBALS[onlineusers] = $ocnt;
      $GLOBALS[idleusers] = $icnt;
      $GLOBALS[offlineusers] = $fcnt;
      $GLOBALS[maxusers] = $cnt;

      $GLOBALS[onlinenames] = $ousers;
      $GLOBALS[idlenames] = $iusers;
      $GLOBALS[offlinenames] = $fusers;

      $GLOBALS[online][isintable] = $bool;
      }

      function updateLastOnline () {
      #magic
      if ($GLOBALS[online][isintable])
      mysql_query("UPDATE ".$GLOBALS[cfg][lastonlinedb]." SET timestamp='".
      time()."' WHERE openid='".$_SESSION[openid_identifier]."';");
      else if ((! empty($_SESSION[openid_identifier]) AND (! empty($_SESSION[myname]))))
      mysql_query("INSERT INTO ".$GLOBALS[cfg][lastonlinedb]." (openid,timestamp,name,chatsubscr) VALUES ('".
      $_SESSION[openid_identifier]."', '".time()."', '".$_SESSION[myname]."', 'a:1:{i:0;s:1:\"3\";}');");
      }

      function jasonOut () {
      #return json as header and exit on ajax requests
      if (($_POST[job] == "update") OR ($_POST[job] == "status") OR
      ($_POST[myjob] == "update") OR ($_POST[myjob] == "status") OR
      ($_POST[ajax] == 1)) {

      #always send possible openid
      $GLOBALS[myreturn][openid_identifier] = $_SESSION[openid_identifier];

      #send debug to js
      $GLOBALS[myreturn][debug] = $_SESSION[jsdebug];

      #did we fell offline?
      if ($GLOBALS[forcelogout] AND (! $_SESSION[freshlogin]))
      $GLOBALS[myreturn][felloffline] = 1;

      #default update and status requests from js
      if (! $_POST[ajax]) {
      $GLOBALS[myreturn][onlineusers] = $GLOBALS[onlineusers];
      $GLOBALS[myreturn][idleusers] = $GLOBALS[idleusers];

      #if we are logged in, we have to send messaging informations and detailes online informations also
      if ($_SESSION[loggedin]) {
      $GLOBALS[myreturn][newmsgs] = 0;
      $GLOBALS[myreturn][onlinenames] = $GLOBALS[onlinenames];
      $GLOBALS[myreturn][idlenames] = $GLOBALS[idlenames];
      #				$GLOBALS[myreturn][offlinenames] = $GLOBALS[offlinenames];

      #				$GLOBALS[myreturn][onlinearray] = $GLOBALS[onlinearray];
      #				$GLOBALS[myreturn][idlearray] = $GLOBALS[idlearray];
      #				$GLOBALS[myreturn][offlinearray] = $GLOBALS[offlinearray];

      $GLOBALS[myreturn][aryNames] = $GLOBALS[ajaxuserreturnname];
      $GLOBALS[myreturn][aryOpenID] = $GLOBALS[ajaxuserreturnopenid];
      $GLOBALS[myreturn][aryStatus] = $GLOBALS[ajaxuserreturnstatus];
      $GLOBALS[myreturn][aryTimes] = $GLOBALS[ajaxuserreturntimes];
      $GLOBALS[myreturn][aryNewMessage] = $GLOBALS[ajaxuserreturnnewmessage];

      $tmppa = array();
      $sql = mysql_query("SELECT id,sender FROM ".$GLOBALS[cfg][msg][msgtable]." WHERE receiver='".
      $_SESSION[openid_identifier]."' AND new='1' ORDER BY timestamp DESC;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[myreturn][newmsgs]++;
      $GLOBALS[myreturn][newmsgid] = $row[id];

      if (empty($GLOBALS[users][byuri][$row[sender]][name]))
      $tmpnamea = $GLOBALS[adminmsgname];
      else
      $tmpnamea = $GLOBALS[users][byuri][$row[sender]][name];

      array_push($tmppa, $tmpnamea);
      }
      array_unique($tmppa);
      $GLOBALS[myreturn][newmsgsfrom] = $tmppa;
      }
      }

      #do we have a fresh session
      if ($_SESSION[freshlogin]) {
      $GLOBALS[myreturn][freshlogin] = 1;
      $_SESSION[freshlogin] = 0;
      }	else $GLOBALS[myreturn][freshlogin] = 0;

      #debug output
      if ($GLOBALS[debug]) {
      $GLOBALS[myreturn][maxusers] = "X".rand(0, 9);
      $m_time = explode(" ",microtime());
      $totaltime = (($m_time[0] + $m_time[1]) - $starttime);
      $GLOBALS[myreturn][runtime] = round($totaltime,3);
      } else {
      $GLOBALS[myreturn][maxusers] = $GLOBALS[maxusers];
      }

      #request log update of json output
      if ($GLOBALS[reqdebugid])
      $sql = mysql_query("UPDATE ".$GLOBALS[cfg][requestlogtable]." SET output='".
      json_encode($GLOBALS[myreturn])."' WHERE id='".$GLOBALS[reqdebugid]."';");

      #should we force send a json object?
      if ($GLOBALS[jsonobject])
      header('X-JSON: '.json_encode($GLOBALS[myreturn], JSON_FORCE_OBJECT));
      else
      header('X-JSON: '.json_encode($GLOBALS[myreturn]));

      #javascript exit :D
      exit;
      }
      }

      function encodeme($me) {
      return mysql_real_escape_string(htmlspecialchars(str_replace('&', '&amp;', trim($me))));
      }

      function xmppencode($me) {
      return utf8_encode(htmlspecialchars($me));
      }

      function msg ($msg) {
      if ($GLOBALS[debug])
      echo strftime($GLOBALS[cfg][strftime], time())."\t".$msg."\n";
      }

      function updateTimestamp($openid) {
      $sqltsu = mysql_query("UPDATE ".$GLOBALS[cfg][lastonlinedb]." SET timestamp='".time()."' WHERE openid='".$openid."';");
      }

      ################## Chat / Messaging Functions ##################

      function getAllChatChannels ($owner = NULL) {
      $tret = array();
      $count = 0;
      if ($owner) $search = " WHERE owner='".$owner."'";
      else $search = " WHERE 1";

      $sql = mysql_query("SELECT id,owner,name,allowed,created,lastmessage FROM ".$GLOBALS[cfg][chat][channeltable].$search.";");
      while ($row = mysql_fetch_array($sql)) {
      if ($row[owner] == 0)
      $owner = "Willhelm";
      else
      $owner = genUserLink($GLOBALS[users][byname][strtolower($GLOBALS[users][bychat][$row[owner]])]);  #

      $tret[$count][id] = $row[id];
      $tret[$count][owner] = $row[owner];
      $tret[$count][ownername] = $owner;
      $tret[$count][name] = $row[name];
      $tret[$count][allowed] = $row[allowed];
      $tret[$count][created] = getNiceAge($row[created]);
      $tret[$count][lastmessage] = getNiceAge($row[lastmessage]);
      $count++;
      }
      return $tret;
      }

      function getMyChatChannels () {
      $tret = array();
      $count = 0;

      $bool = 1; $wtmp = ""; $wsearch = "";
      foreach ($GLOBALS[chat][subscr] as $subs => $chanid) {
      if ($bool) $bool = 0;
      else $wtmp = " OR";
      $wsearch .= $wtmp." id='".$chanid."'";
      }

      if (! $bool) {
      $sql = mysql_query("SELECT id,owner,name,allowed,created,lastmessage FROM ".
      $GLOBALS[cfg][chat][channeltable].$search." WHERE ".$wsearch.";");
      while ($row = mysql_fetch_array($sql)) {
      if ($row[owner] == 0)
      $owner = "Willhelm";
      else
      $owner = $GLOBALS[users][byuri][$GLOBALS[users][bychat][$row[owner]]][name];

      $tret[$count][id] = $row[id];
      $tret[$count][owner] = $row[owner];
      $tret[$count][ownername] = $owner;
      $tret[$count][name] = $row[name];
      $tret[$count][allowed] = $row[allowed];
      $tret[$count][created] = getNiceAge($row[created]);
      $tret[$count][lastmessage] = getNiceAge($row[lastmessage]);
      $count++;
      }
      }
      return $tret;
      }

      function getChatChannel ($myid) {
      $tret = array();
      $count = 0;

      $sql = mysql_query("SELECT id,owner,name,allowed,created,lastmessage FROM ".
      $GLOBALS[cfg][chat][channeltable]." WHERE id='".$myid."';");
      while ($row = mysql_fetch_array($sql)) {
      if ($row[owner] == 0)
      $owner = "Willhelm";
      else
      $owner = $GLOBALS[users][byuri][$GLOBALS[users][bychat][$row[owner]]][name];

      $tret[id] = $row[id];
      $tret[owner] = $row[owner];
      $tret[ownername] = $owner;
      $tret[name] = $row[name];
      $tret[allowed] = $row[allowed];
      $tret[created] = strftime($GLOBALS[cfg][strftime], $row[created]);
      $tret[lastmessage] = getNiceAge($row[lastmessage]);
      }
      return $tret;
      }

      function getMyChatMessages ($since = NULL) {
      $data = getMyChatChannels();
      $tret = array();
      $count = 0;

      if (empty($since)) $stmp = "";
      else $stmp = " AND timestamp<'".$since."'";

      $bool = 1; $wtmp = ""; $wsearch = "";
      foreach ($GLOBALS[chat][subscr] as $subs => $chanid) {
      if ($bool) $bool = 0;
      else $wtmp = " OR";
      $wsearch .= $wtmp." channel='".$chanid."'";
      $tret[msg][$chanid] = array();
      }

      if (! $bool) {
      $sql = mysql_query("SELECT id,sender,channel,timestamp,message FROM ".$GLOBALS[cfg][chat][msgtable].
      " WHERE".$wsearch." ORDER BY timestamp DESC LIMIT 20;");
      while ($row = mysql_fetch_array($sql)) {
      $tret[msg][$count][id] = $row[id];
      $tret[msg][$count][channel] = $row[channel];
      $tret[msg][$count][sender] = $GLOBALS[users][byuri][$GLOBALS[users][bychat][$row[sender]]][name];
      $tret[msg][$count][ts] = getAge($row[timestamp]);
      $tret[msg][$count][msg] = $row[message];
      $count++;
      }
      }
      $tret[chan] = $data;
      return $tret;
      }

      function getMyChatMessagesFrom ($channel) {
      $tret = array();
      $count = 0;

      $sql = mysql_query("SELECT id,sender,channel,timestamp,message FROM ".$GLOBALS[cfg][chat][msgtable].
      " WHERE channel='".$channel."' ORDER BY timestamp DESC LIMIT 20;");
      while ($row = mysql_fetch_array($sql)) {
      $tret[$count][id] = $row[id];
      $tret[$count][channel] = $row[channel];
      $tret[$count][sender] = $GLOBALS[users][bychat][$row[sender]];
      $tret[$count][timestamp] = getNiceAge($row[timestamp]);
      $tret[$count][msg] = $row[message];
      $count++;
      }
      return $tret;
      }

      function genAllowedCheckbox ($template = NULL) {
      $tret = "<table>\n"; $walk = 1; $max = 5;
      foreach ($GLOBALS[users][byuri] as $myuri) {
      if ($walk == ($max + 1)) $walk = 1;
      if ($walk == 1) $tret .= "<tr>\n";

      if ($template)
      if (in_array($myuri[chat], $template)) $check = " checked";
      else $check = "";

      if (empty($myuri[chat])) $dis = " DISABLED";
      else $dis = "";

      $tret  .= "<td><input type='checkbox' name='allowed[]' value='".$myuri[chat]."' ".$check.$dis."/> ".$myuri[name]."</td>\n";
      if ($walk == $max) $tret .= "</tr>\n";
      $walk++;
      }
      for ($i = $walk; $i <= $max; $i++) $tret .= "<td>&nbsp</td>\n";
      if ($walk != $max) $tret .= "</tr>\n";
      return $tret."</table>\n";
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

      #eqdkp
      if (! empty($GLOBALS[users][byuri][$myuser][eqdkp])) {
      $GLOBALS[html] .= "- modifying eqdkp user ".$GLOBALS[users][byuri][$myuser][eqdkp]." :)<br />";
      $sql = mysql_query("SELECT auth_id,auth_value FROM eqdkp_auth_options WHERE 1 ORDER BY auth_id asc;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[module][eqdkp][$row[auth_id]] = $row[auth_value];
      }

      $sql = mysql_query("SELECT user_id FROM eqdkp_users WHERE username='".
      $GLOBALS[cfg][profile][$myprofile][eqdkp]."';");
      while ($row = mysql_fetch_array($sql))
      $tmpid = $row[user_id];

      $sql = mysql_query("SELECT auth_id,auth_setting FROM eqdkp_auth_users WHERE user_id='".
      $tmpid."' ORDER BY auth_id asc;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[module][eqdkp2][$row[auth_id]] = $row[auth_setting];
      }

      $sql = mysql_query("SELECT auth_id,auth_setting FROM eqdkp_auth_users WHERE user_id='".
      $GLOBALS[users][byuri][$_POST[user]][eqdkp]."' ORDER BY auth_id asc;");
      while ($row = mysql_fetch_array($sql)) {
      $GLOBALS[module][eqdkp3][$row[auth_id]] = $row[auth_setting];
      }

      #foreach auth_id
      foreach (array_keys($GLOBALS[module][eqdkp]) as $myname) {
      #is empty
      if (empty($GLOBALS[module][eqdkp3][$myname])) {
      $mode = 1;
      } else {
      $mode = 0;
      }

      if (empty($GLOBALS[module][eqdkp2][$myname])) {
      $value = "N";
      } else {
      $value = $GLOBALS[module][eqdkp2][$myname];
      }

      if ($mode) {
      $sql = mysql_query("INSERT INTO eqdkp_auth_users (user_id, auth_id, auth_setting) VALUES ('".
      $GLOBALS[users][byuri][$myuser][eqdkp]."', '".$myname."', '".$value."');");
      } else {
      $sql2 = mysql_query("UPDATE eqdkp_auth_users SET auth_setting='".$value."' WHERE user_id='".
      $GLOBALS[users][byuri][$myuser][eqdkp]."' AND auth_id='".$myname."';");
      }

      $sqlz = mysql_query("UPDATE eqdkp_users SET user_active='1', user_lang='german' WHERE user_id='".
      $GLOBALS[users][byuri][$myuser][eqdkp]."';");
      }
      }

      # set openid profile
      $GLOBALS[html] .= "- modifying openid user :)<br />";
      $sql = mysql_query("UPDATE ".$GLOBALS[cfg][userprofiletable]." SET role='".$myprofile."' WHERE openid='".$myuser."';");

      $GLOBALS[html] .= "<h3>=&gt; Changes done</h3>";
      }

      function checkProfile ($myOpenID = '') {
      if (empty($myOpenID))
      $myOpenID = $_SESSION[openid_identifier];

      if (empty($myOpenID))
      return 0;

      #do we have to update our user profile?
      $tmpRegistred = 0;
      $tmpNeedUpdate = 0;
      $sql = mysql_query("SELECT accurate,nickname,email,surname,forename,dob,mob,yob,sex FROM ".$GLOBALS[cfg][userprofiletable]." WHERE openid='".$myOpenID."';");
      while ($row = mysql_fetch_array($sql)) {
      $tmpRegistred = 1;

      if ((! empty($row[nickname])) AND (! empty($row[email])) AND (! empty($row[surname])) AND
      (! empty($row[forename])) AND $row[dob] AND $row[mob] AND	$row[yob]) // AND (! empty($row[sex])))
      $tmpNeedUpdate = 0;
      else
      $tmpNeedUpdate = 1;

      if (($tmpNeedUpdate == 0) AND ($row[accurate] == 1))
      $tmpNeedUpdate = 0;
      else
      $tmpNeedUpdate = 1;
      }

      if ($tmpNeedUpdate)
      return 1;
      else
      return 0;
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

      function fetchArmoryCharacter ($charname) {
      if (!isset($GLOBALS[armorycharupdatecount]))
      $GLOBALS[armorycharupdatecount] = 0;

      #name, timestamp, content, level, genderid, classid, raceid, ilevelavg
      $mychar = "";
      unset($char);
      $sql = mysql_query("SELECT id,timestamp,ilevelavg,name,level,genderid,classid,raceid,factionid,pvpkills,skills,achievments FROM ".
      $GLOBALS[cfg][armory][charcachetable]." WHERE name LIKE '".$charname."' ORDER BY timestamp ASC;");
      #FIXME geht die suche noch?
      $mychar[level] = null;
      while ($row = mysql_fetch_array($sql)) {
      if (strtolower($charname) == strtolower($row[name])) {
      $mychar[id]						= $row[id];
      $mychar[timestamp]		= $row[timestamp];
      $mychar[ilevelavg]		= $row[ilevelavg];
      $mychar[name]					= $row[name];
      $mychar[level]				= $row[level];
      $mychar[genderid]			= $row[genderid];
      $mychar[classid]			= $row[classid];
      $mychar[raceid]				= $row[raceid];
      $mychar[factionid]		= $row[factionid];
      $mychar[pvpkills]			= $row[pvpkills];
      $mychar[skills]				= unserialize($row[skills]);
      $mychar[achievments]	= unserialize($row[achievments]);
      break;
      }
      }
      #check if char is in db and accurate, if not, fetch online
      if ((($mychar[timestamp] + $GLOBALS[armorychartimeout])  < time())
      AND ($GLOBALS[armorycharupdatecount] < $GLOBALS[armorycharmaxupdate])) {
      $mychar[content] = fetchArmoryXML ("n", $charname);
      if (! $GLOBALS[armorydown]) {
      if (strpos($mychar[content], '<character ')) {
      $GLOBALS[armorycharupdatecount]++;
      $mychar[timestamp] = time();
      $char = new SimpleXMLElement($mychar[content]);
      $myilvl = genArmoryIlvl($char);
      $myskills = genArmorySkills($char);
      $myachievments = genArmoryAchievments($char);
      if ($mychar[id] AND $char->characterInfo->character['level']) {
      sysmsg ("Fetching data from Armory due old Database entry for Char: ".$charname, 3);
      $sql = "UPDATE ".$GLOBALS[cfg][armory][charcachetable]." SET ".
      "timestamp='".$mychar[timestamp]."', ".
      "content='".mysql_real_escape_string($mychar[content])."', ".
      "level='".$char->characterInfo->character['level']."', ".
      "genderid='".$char->characterInfo->character['genderId']."', ".
      "classid='".$char->characterInfo->character['classId']."', ".
      "raceid='".$char->characterInfo->character['raceId']."', ".
      "factionid='".$char->characterInfo->character['factionId']."', ".
      "pvpkills='".$char->characterInfo->characterTab->pvp->lifetimehonorablekills['value']."', ".
      "skills='".serialize($myskills)."', ".
      "achievments='".serialize($myachievments)."', ".
      "ilevelavg='".$myilvl."' WHERE id='".$mychar[id]."';";
      } else {
      sysmsg ("Fetching nonexisting Char from Armory: ".$charname, 3);
      $sql = "INSERT INTO ".$GLOBALS[cfg][armory][charcachetable]." SET ".
      "name='".$char->characterInfo->character['name']."', ".
      "timestamp='".$mychar[timestamp]."', ".
      "content='".mysql_real_escape_string($mychar[content])."', ".
      "level='".$char->characterInfo->character['level']."', ".
      "genderid='".$char->characterInfo->character['genderId']."', ".
      "classid='".$char->characterInfo->character['classId']."', ".
      "raceid='".$char->characterInfo->character['raceId']."', ".
      "factionid='".$char->characterInfo->character['factionId']."', ".
      "pvpkills='".$char->characterInfo->characterTab->pvp->lifetimehonorablekills['value']."', ".
      "skills='".serialize($myskills)."', ".
      "achievments='".serialize($myachievments)."', ".
      "ilevelavg='".$myilvl."';";
      }
      if (! empty($char->characterInfo->character['name']))
      $sqlr = mysql_query($sql);

      $mychar[ilevelavg] = $myilvl;
      $mychar[name]				= (string) $char->characterInfo->character['name'];
      $mychar[timestamp]	= (string) $char->characterInfo->character['timestamp'];
      $mychar[level]			= (string) $char->characterInfo->character['level'];
      $mychar[genderid]		= (string) $char->characterInfo->character['genderId'];
      $mychar[classid]		= (string) $char->characterInfo->character['classId'];
      $mychar[raceid]			= (string) $char->characterInfo->character['raceId'];
      $mychar[factionid]	= (string) $char->characterInfo->character['factionId'];
      $mychar[pvpkills]		= (string) $char->characterInfo->characterTab->pvp->lifetimehonorablekills['value'];
      $mychar[skills]			= $myskills;
      $mychar[achievments]	= $myachievments;
      } else {
      sysmsg ("Fetching data from Armory failed. Armory probably down. XML Length: ".strlen($mychar[content]), 3);
      }
      }
      } else {
      sysmsg ("Fetching data from Database for Char: ".$charname, 3);
      }

      if (empty($mychar[level])) {
      sysmsg ("ERROR fetching character info for ".$charname."!", 3);
      return null;
      } else {
      return $mychar;
      }
      }

      function genArmoryIlvlHtml ($ilvl, $text) {
      $color = "";
      if ($ilvl < 50)
      $color = "#888888";
      elseif ($ilvl < 280)
      $color = "#998888";
      elseif ($ilvl < 329)
      $color = "#aa6666";
      elseif ($ilvl < 340)
      $color = "#cc4444";
      elseif ($ilvl < 350)
      $color = "#dd2222";
      elseif ($ilvl < 360)
      $color = "#ee1111";
      elseif ($ilvl < 370)
      $color = "#ff0000";
      else
      $color = "#48233e";
      return "<span title='Itemlevel Durchschnitt: ".$ilvl."' style='color:".$color.
      "; border-width:1px; border-style:solid; border-color:".$color.";'>".
      $text."</span>";
      }

      function genArmoryClassClass ($ilvl) {
      switch ($ilvl) {
      case "1": $myclass = "inpWarrior"; break;
      case "2": $myclass = "inpPaladin"; break;
      case "3": $myclass = "inpHunter"; break;
      case "4": $myclass = "inpRogue"; break;
      case "5": $myclass = "inpPriest"; break;
      case "6": $myclass = "inpDeathknight"; break;
      case "7": $myclass = "inpShaman"; break;
      case "8": $myclass = "inpMage"; break;
      case "9": $myclass = "inpWarlock"; break;
      case "11": $myclass = "inpDruid"; break;
      default: $myclass = "";
      }
      return $myclass;
      }

      function getArmoryUserOfChar ($char) {
      $return = array();
      foreach ($GLOBALS[users][byuri] as $myuser)
      if (! empty($myuser[armorychars]))
      foreach ($myuser[armorychars] as $mychar)
      if (strtolower($mychar) == strtolower($char))
      array_push($return, $myuser[uri]);
      return $return;
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
      #check if char is in db and accurate, if not, fetch online
      if (! $myitem[level]) {
      sysmsg ("Fetching nonexisting Item from Armory: ".$itemid, 3);
      $myitem[content] = fetchArmoryXML ("i", $itemid);
      $myitem[timestamp] = time();
      $item = new SimpleXMLElement($myitem[content]);
      if ((string) $item->itemInfo->item['id']) {
      $sql = "INSERT INTO ".$GLOBALS[cfg][armory][itemcachetable]." SET ".
      "name='".$item->itemInfo->item['name']."', ".
      "icon='".$item->itemInfo->item['icon']."', ".
      "timestamp='".time()."', ".
      "level='".$item->itemInfo->item['level']."', ".
      "id='".$item->itemInfo->item['id']."', ".
      "quality='".$item->itemInfo->item['quality']."', ".
      "type='".$item->itemInfo->item['type']."';";
      $myitem[name]				= (string) $item->itemInfo->item['name'];
      $myitem[timestamp]	= (string) $item->itemInfo->item['timestamp'];
      $myitem[level]			= (string) $item->itemInfo->item['level'];
      $myitem[id]					= (string) $item->itemInfo->item['id'];
      $myitem[quality]		= (string) $item->itemInfo->item['quality'];
      $myitem[type]				= (string) $item->itemInfo->item['type'];
      $myitem[icon]				= (string) $item->itemInfo->item['icon'];
      echo $sql;
      $sqlr = mysql_query($sql);
      }
      }
      if (! count($myitem[icon]))
      $myitem[icon] = "404";
      return $myitem;
      }

      function genArmoryCharHtml ($name, $classid, $raceid, $genderid, $factionid) {
      #FIXME! hardcoded armory module name
      return "<a href='?module=wowarmory&mydo=showchardetail&mycharname=".$name."'><span class='".genArmoryClassClass($classid)."' title='".showArmoryName("race", $raceid).
      ", ".showArmoryName("gender", $genderid).", ".showArmoryName("faction", $factionid).
      "'>".$name." ".
      "</span></a>";
      }

      function genArmoryItemHtml ($myitem, $charlvl = 0, $pcs = "") {
      # http://wow.allakhazam.com/images/icons/icons.tar.gz
      $id = (integer) $myitem->attributes()->id;
      $ench = (integer) $myitem->attributes()->permanentenchant;
      $rand = (integer) $myitem->attributes()->randomPropertiesId;
      $lvl = (integer) $myitem->attributes()->lvl;
      $gems = ""; $gbool = false;
      if ((integer) $myitem->attributes()->gem0Id)
      $gems .= (integer) $myitem->attributes()->gem0Id;
      if ((integer) $myitem->attributes()->gem1Id)
      $gems .= ":".(integer) $myitem->attributes()->gem1Id;
      if ((integer) $myitem->attributes()->gem2Id)
      $gems .= ":".(integer) $myitem->attributes()->gem2Id;
      if (! empty($pcs))
      $pcs = "&pcs=".$pcs;

      #FIXME! hardcoded armory module name
      $item = fetchArmoryItem($id);
      #	$ret = "<img src='/img/wowarmory/".$item[icon].".png' align='left' style='padding:3px;width:26px;height:26px;'>".$item[name]."<br />lvl: ".$item[level].", ".$item[type];
      $ret = "<a href='#' rel='domain=de&item=".$id."&lvl=".$charlvl."&ench=".$ench."&rand=".$rand."&gems=".$gems.$pcs.
      "'><img src='/img/armory/".$item[icon].".png' align='left' style='padding:3px;width:26px;height:26px;'></a>";

      return $ret;
      }

      ################## Template Enginge Functions ##################

      function templGetFile ($filename) {
      return file_get_contents($GLOBALS[cfg][moduledir]."/".$_POST[module]."/html/".$filename);
      }

      function templReplText ($content, $search, $replace) {
      return str_replace("MY".$search."REPLACE", $replace, $content);
      }

      function templGenDropdown ($name, $from, $to, $selected) {
      $dd = "<select name='".$name."'>";
      for ($i = $from; $i <= $to; $i++) {
      if ($selected == $i)
      $mytmp = " selected";
      else
      $mytmp = "";
      $dd .= "<option".$mytmp.">".$i."</option>\n";
      }
      $dd .= "</select>\n";
      return $dd;
      }

      ################## Multigaming Functions ##################

      function getMultigamingGames () {
      $sql = mysql_query("SELECT * FROM ".$GLOBALS[cfg][mg][gamestable]." WHERE 1;");
      while ($row = mysql_fetch_array($sql)) {
      $mg[$row[id]][name] = $row[name];
      $mg[$row[id]][url] = $row[url];
      $mg[$row[id]][comment] = $row[comment];
      }
      return $mg;
      }

      function getMultigamingList ($user, $type = "table") {
      $games = getMultigamingGames();
      $ret = "";
      switch ($type) {
      case "table":
      if(! empty($GLOBALS[users][byuri][$user][armorychars])) {
      $ret .= "<tr><td style='vertical-align:top;'>WOW</td><td style='vertical-align:top;'>";
      foreach ($GLOBALS[users][byuri][$user][armorychars] as $mychar)
      if ($char = fetchArmoryCharacter($mychar))
      $ret .= genArmoryIlvlHtml($char[ilevelavg],$char[level]).
      genArmoryCharHtml($char[name], $char[classid], $char[raceid], $char[genderid], $char[factionid])." ";
      $ret .= "</td></tr>";
      }
      if (! empty($GLOBALS[users][byuri][$user][multigaming]))
      foreach ($GLOBALS[users][byuri][$user][multigaming] as $mygame)
      foreach ($mygame as $myname)
      if (! empty($myname))
      $ret .= "<tr><td style='vertical-align:top;'><a href='".$games[key($mygame)][url].
      "' target='new' title='".$games[key($mygame)][comment].
      "'>".$games[key($mygame)][name]."</a></td><td style='vertical-align:top;'>".$myname."</td></tr>";
      if (! empty($ret))
      $ret = "<br /><table><tr><th>Game</th><th>Name</th></tr>".$ret."</table>";
      break;

      case "inline":
      if(! empty($GLOBALS[users][byuri][$user][armorychars])) {
      $ret .= "<abbr title='";
      foreach ($GLOBALS[users][byuri][$user][armorychars] as $mychar)
      if ($char = fetchArmoryCharacter($mychar))
      $ret .= $char[name]." ";
      $ret .= "'>WOW</abbr> ";
      }
      if (! empty($GLOBALS[users][byuri][$user][multigaming])) {
      foreach ($GLOBALS[users][byuri][$user][multigaming] as $mygame)
      foreach ($mygame as $myname)
      if (! empty($myname))
      $ret .= "<abbr title='".$myname."'>".$games[key($mygame)][name]."</abbr> ";
      }
      break;
      }
      return $ret;
      }

     */
}

?>
