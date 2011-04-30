<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerPortal.php';
require_once 'Model/ImbaNavigation.php';
require_once 'Controller/ImbaUserContext.php';

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

        foreach ($this->managerPortal->selectAll() as $tmpPortal) {
            if (count($tmpPortal->getAliases())) {
                foreach ($tmpPortal->getAliases() as $tmpAlias) {
                    if ($_SERVER[HTTP_HOST] == $tmpAlias) {
                        $this->loadPortalContext = $tmpPortal->getId();
                    }
                }
            }
        }
        if ($this->loadPortalContext == null) {
            $this->loadPortalContext = ImbaUserContext::getPortalContext();
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
     * Display Portal Navigation
     */
    public function displayLoaderPortalNavigation() {
        return "<div id='imbaNavigationPortal'></div>";
    }

    /**
     * Render Portal Navigation
     */
    public function renderPortalNavigation($portalId) {
        $return = "";

        /**
         * Set up the portal navigation
         */
        $portal = $this->managerPortal->selectById($portalId);
        foreach ($portal->getPortalEntries() as $portalEntry) {
            $showMe = false;
            if (ImbaUserContext::getUserRole() >= $portalEntry->getRole()) {
                $showMe = false;
                if ((ImbaUserContext::getLoggedIn() && $portalEntry->getLoggedin()) || ($portalEntry->getLoggedin() == 0)) {
                    $showMe = true;
                }
            }
            if ($showMe) {
                $return .= "<li><a href='" . $portalEntry->getUrl() . "' title='" . $portalEntry->getComment() . "'>" . $portalEntry->getName() . "</a></li>";
            }
        }
        return $return;
    }

    public function renderImbaAdminNavigation() {
        $return = "<li>";
        $return .= "<a id='imbaMenuImbAdmin' href='javascript:void(0)' onclick='javascript: loadImbaAdminDefaultModule();' title='";
        $return .= ImbaConstants::$WEB_IMBADMIN_BUTTON_COMMENT . "'>" . ImbaConstants::$WEB_IMBADMIN_BUTTON_NAME . "</a>";
        $return .= "<ul class='subnav'>";
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
                            $return .= "<li><a href='javascript:void(0)' onclick='javascript: loadImbaAdminModule(\\\"" . $modIdentifier . "\\\");' title='" . $Navigation->getComment($nav) . "'>" . $Navigation->getName($nav) . "</a></li>";
                            array_push($identifiers, $modIdentifier);
                            $Navigation = null;
                        }
                    }
                }
            }
            closedir($handle);
        }
        $return .= "</ul>";
        $return .= "</li>";
        return "<div id='imbaNavigationImbaAdmin'>" . $return . "</div>";
    }

    public function renderImbaGameNavigation() {
        $return = "<li>";
        $return .= "<a id='imbaMenuImbaGame' href='javascript:void(0)' onclick='javascript: loadImbaGameDefaultGame();' title='";
        $return .= ImbaConstants::$WEB_IMBAGAME_BUTTON_COMMENT . "'>" . ImbaConstants::$WEB_IMBAGAME_BUTTON_NAME . "</a>";
        $return .= "<ul class='subnav'>";
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
                            $return .= "<li><a href='javascript:void(0)' onclick='javascript: loadImbaGame(\\\"" . $modIdentifier . "\\\");' title='" . $Navigation->getComment($nav) . "'>" . $Navigation->getName($nav) . "</a></li>";
                            array_push($identifiers, $modIdentifier);
                            $Navigation = null;
                        }
                    }
                }
            }
            closedir($handle);
        }

        $return .= "</ul>";
        $return .= "</li>";
        return "<div id='imbaNavigationImbaGame'>" . $return . "</div>";
    }

    /**
     * Render the Portal Chooser Dropdown
     */
    public function renderPortalChooser() {
        $managerPortal = ImbaManagerPortal::getInstance();

        $return = "<li>";
        $return .= "<a id='imbaMenuImbaPortal' href='javascript:void(0)' onclick='javascript: loadImbaPortal(-1);' title='Portal Zur&uuml;cksetzen'>Portal</a>";
        $return .= "<ul class='subnav'>";
        foreach ($managerPortal->selectAll() as $portal) {
            $return .= "<li style='vertical-align: middle;'><a href='javascript:void(0)' onclick='javascript: loadImbaPortal(\\\"" . $portal->getId() . "\\\");' title='" . $portal->getComment() . "'>";
            $return .= "<img src='" . $portal->getIcon() . "' width='24px' height='24px' style='float: left;' /> " . $portal->getName();
            $return .= "</a></li>";
        }
        $return .= "</ul>";
        $return .= "</li>";
        return "<div id='imbaNavigationPortalChooser'>" . $return . "</div>";
    }

}

?>
