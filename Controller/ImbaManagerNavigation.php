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
     * our portal context
     */
    private $loadPortalContext = null;
    private $managerPortal = null;

    /**
     * Ctor
     */
    protected function __construct() {
        //parent::__construct();
        $this->database = ImbaManagerDatabase::getInstance();

        $this->loadPortalContext = ImbaConstants::$SETTINGS['DEFAULT_PORTAL_ID'];
        $this->managerPortal = ImbaManagerPortal::getInstance();

        if (ImbaUserContext::getPortalContext()) {
            $this->loadPortalContext = ImbaUserContext::getPortalContext();
        } else {
            foreach ($this->managerPortal->selectAll() as $tmpPortal) {
                if (count($tmpPortal->getAliases())) {
                    foreach ($tmpPortal->getAliases() as $tmpAlias) {
                        if ($_SERVER[HTTP_HOST] == $tmpAlias) {
                            $this->loadPortalContext = $tmpPortal->getId();
                        }
                    }
                }
            }
        }
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
        if ($this->managerPortal->selectById($this->loadPortalContext) != null) {
            $portal = $this->managerPortal->selectById($this->loadPortalContext);
            foreach ($portal->getNavitems() as $navElement) {
                $return .= "<li><a href='" . $navElement->getUrl() . "' title='" . $navElement->getComment() . "'>" . $navElement->getName() . "</a></li>\\\n";
            }
        }

        /**
         * Workaround. delete after protal magic works
         */
        if (empty($return)) {
            $topNav = new ImbaTopNavigation();
            echo ImbaSharedFunctions::getDomain($_SERVER['HTTP_REFERER']); exit;
            switch (ImbaSharedFunctions::getDomain($_SERVER['HTTP_REFERER'])) {
                case "http://www.oom.ch/": //OOM
                case "http://oom.ch/":
                    $topNav->addElement("blog", "Blog", "_top", "https://oom.ch/blog/", "OOM Blog");
                    $topNav->addElement("wiki", "Wiki", "_top", "https://oom.ch/wiki/", "OOM Wiki");
                    break;
                case "http://b.oom.ch/": //EVE
                    $topNav->addElement("forum", "Forum", "_top", "http://b.oom.ch/forum/", "the Dudez Forum");
                    $topNav->addElement("killboard", "Killboard", "_top", "http://b.oom.ch/kb/", "the Dudez Killboard");
                    break;
                case "http://www.alptroeim.ch/": //WOW
                case "http://alptroeim.ch/":
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
        $return .= "<a id='imbaMenuImbaGame' href='javascript:void(0)' onclick='javascript: loadImbaGameDefaultGame();' title='";
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

    /**
     * Render the Portal Chooser Dropdown
     */
    public function renderPortalChooser() {
        $managerPortal = ImbaManagerPortal::getInstance();

        $return = "<li>\\\n";
        $return .= "<a id='imbaMenuImbaPortal' href='javascript:void(0)' onclick='javascript: loadImbaPortal(-1);' title='Portal Zur&uuml;cksetzen'>Portal</a>\\\n";
        $return .= "<ul class='subnav'>\\\n";
        foreach ($managerPortal->selectAll() as $portal) {
            $return .= "<li style='vertical-align: middle;'><a href='javascript:void(0)' onclick='javascript: loadImbaPortal(\\\"" . $portal->getId() . "\\\");' title='" . $portal->getComment() . "'>";
            $return .= "<img src='" . $portal->getIcon() . "' width='24px' height='24px' style='float: left;' /> " . $portal->getName();
            $return .= "</a></li>\\\n";
        }
        $return .= "</ul>\\\n";
        $return .= "</li>\\\n";
        return $return;
    }

}

?>
