<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerPortal.php';
require_once 'Model/ImbaNavigation.php';

/**
 *  Controller / Manager for Top Navigation
 *  - insert, update, delete navigation entries
 * 
 */
class ImbaManagerNavigation extends ImbaManagerBase {

    /**
     * ImbaManagerDatabase
     */
    protected $navEntriesCached = null;
    /**
     * Singleton implementation
     */
    private static $instance = null;

    /**
     * Ctor
     */
    protected function __construct() {
        //parent::__construct();
        $this->database = ImbaManagerDatabase::getInstance();
    }

    /*
     * Singleton init
     */

    public static function getInstance() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Render Portal Navigation
     */
    public function renderPortalNavigation() {
        $return = "";

        /**
         * Set up the portal navigation
         */
        /**
         * New Portal Code
         */
        $loadPortalContext = ImbaConstants::$SETTINGS['DEFAULT_PORTAL_ID'];
        $managerPortal = ImbaManagerPortal::getInstance();
        if (ImbaUserContext::getPortalContext()) {
            $loadPortalContext = ImbaUserContext::getPortalContext();
        } else {
            foreach ($managerPortal->selectAll() as $tmpPortal) {
                if (count($tmpPortal->getAliases())) {
                    foreach ($tmpPortal->getAliases() as $tmpAlias) {
                        if ($_SERVER[HTTP_HOST] == $tmpAlias) {
                            $loadPortalContext = $tmpPortal->getId();
                        }
                    }
                }
            }
        }
        if ($managerPortal->selectById($loadPortalContext) != null) {
            $portal = $managerPortal->selectById($loadPortalContext);
            foreach ($portal->getNavitems() as $navElement) {
                $return .= "<li><a href='" . $navElement->getUrl() . "' title='" . $navElement->getComment() . "'>" . $navElement->getName() . "</a></li>\\\n";
            }
        }

        /**
         * Workaround. delete after protal magic works
         */
        if (empty($return)) {
            $topNav = new ImbaTopNavigation();
            switch ($_SERVER[HTTP_HOST]) {
                case "www.oom.ch": //OOM
                case "oom.ch":
                    $topNav->addElement("blog", "Blog", "_top", "https://oom.ch/blog/", "OOM Blog");
                    $topNav->addElement("wiki", "Wiki", "_top", "https://oom.ch/wiki/", "OOM Wiki");
                    break;
                case "b.oom.ch": //EVE
                    $topNav->addElement("forum", "Forum", "_top", "http://b.oom.ch/forum/", "the Dudez Forum");
                    $topNav->addElement("killboard", "Killboard", "_top", "http://b.oom.ch/kb/", "the Dudez Killboard");
                    break;
                case "www.alptroeim.ch": //WOW
                case "alptroeim.ch":
                default:
                    $topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
                    $topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
                    break;
            }
            /**
             * Render Top Navigation Entries
             */
            foreach ($topNav->getElements() as $nav) {
                $return .= "<li><a href='" . $topNav->getElementUrl($nav) . "' title='" . $topNav->getElementComment($nav) . "'>" . $topNav->getElementName($nav) . "</a></li>\\\n";
            }
        }
        return $return;
    }

    public function renderImbaAdminNavigation() {
        $return = "<li>\\\n";
        $return .= "<a id='imbaMenuImbAdmin' href='javascript:void(0)' onclick='javascript: loadImbaAdminDefaultModule();' title='";
        $return .= ImbaConstants::$WEB_IMBADMIN_BUTTON_COMMENT . "'>" . ImbaConstants::$WEB_IMBADMIN_BUTTON_NAME . "</a>\\\n";
        $return .= "<ul class='subnav'>\\\n";
        $contentNav = new ImbaContentNavigation();
        if ($handle = opendir('Ajax/IMBAdminModules/')) {
            $identifiers = array();
            while (false !== ($file = readdir($handle))) {
                if (strrpos($file, ".Navigation.php") > 0) {
                    include 'Ajax/IMBAdminModules/' . $file;
                    if (ImbaUserContext::getUserRole() >= $Navigation->getMinUserRole()) {
                        $showMe = false;
                        if (ImbaUserContext::getLoggedIn() && $Navigation->getShowLoggedIn()) {
                            $showMe = true;
                        } elseif ((!ImbaUserContext::getLoggedIn()) && $Navigation->getShowLoggedOff()) {
                            $showMe = true;
                        }

                        if ($showMe) {
                            $modIdentifier = trim(str_replace(".Navigation.php", "", $file));
                            $return .= "<li><a href='javascript:void(0)' onclick='javascript: loadImbaAdminModule(\\\"" . $modIdentifier . "\\\");' title='" . $Navigation->getComment($nav) . "'>" . $Navigation->getName($nav) . "</a></li>\\\n";
                            array_push($identifiers, $modIdentifier);
                            $Navigation = null;
                        }
                    }
                }
            }
            closedir($handle);
        }
        $return .= "</ul>\\\n";
        $return .= "</li>\\\n";
        return $return;
    }

    public function renderImbaGameNavigation() {
        $return = "<li>\\\n";
        $return .= "<a id='imbaMenuImbAdmin' href='javascript:void(0)' onclick='javascript: loadImbaGameDefaultGame();' title='";
        $return .= ImbaConstants::$WEB_IMBAGAME_BUTTON_COMMENT . "'>" . ImbaConstants::$WEB_IMBAGAME_BUTTON_NAME . "</a>\\\n";
        $return .= "<ul class='subnav'>\\\n";
        $contentNav = new ImbaContentNavigation();
        if ($handle = opendir('Ajax/IMBAdminGames/')) {
            $identifiers = array();
            while (false !== ($file = readdir($handle))) {
                if (strrpos($file, ".Navigation.php") > 0) {
                    include 'Ajax/IMBAdminGames/' . $file;
                    if (ImbaUserContext::getUserRole() >= $Navigation->getMinUserRole()) {
                        $showMe = false;
                        if (ImbaUserContext::getLoggedIn() && $Navigation->getShowLoggedIn()) {
                            $showMe = true;
                        } elseif ((!ImbaUserContext::getLoggedIn()) && $Navigation->getShowLoggedOff()) {
                            $showMe = true;
                        }

                        if ($showMe) {
                            $modIdentifier = trim(str_replace(".Navigation.php", "", $file));
                            $return .= "<li><a href='javascript:void(0)' onclick='javascript: loadImbaGame(\\\"" . $modIdentifier . "\\\");' title='" . $Navigation->getComment($nav) . "'>" . $Navigation->getName($nav) . "</a></li>\\\n";
                            array_push($identifiers, $modIdentifier);
                            $Navigation = null;
                        }
                    }
                }
            }
            closedir($handle);
        }

        $return .= "</ul>\\\n";
        $return .= "</li>\\\n";
        return $return;
    }

}

?>