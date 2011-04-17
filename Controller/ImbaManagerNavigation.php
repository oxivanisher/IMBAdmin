<?php

require_once 'Controller/ImbaManagerBase.php';
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
     * Inserts a user into the Database
     */
    public function insert(ImbaTopNavigation $navEntry) {

        $query = "INSERT INTO %s ";
        $query .= "(handle, name, target, url, comment, loggedin, role) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_NAVITEMS,
            $role->getHandle(),
            $role->getRole(),
            $role->getName(),
            $role->getSmf(),
            $role->getWordpress(),
            $role->getIcon()
        ));
    }

    /**
     * Updates a user into the Database
     */
    public function update(ImbaTopNavigation $navEntry) {
        $query = "UPDATE %s SET ";
        $query .= "handle = '%s', role = '%s', name = '%s', smf = '%s', wordpress = '%s', icon = '%s' ";
        $query .= "WHERE id = '%s'";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_NAVITEMS,
            $role->getHandle(),
            $role->getRole(),
            $role->getName(),
            $role->getSmf(),
            $role->getWordpress(),
            $role->getIcon(),
            $role->getId()
        ));
    }

    /**
     * Delets a Role by Id
     */
    public function delete($id) {
        // TODO: Check if there are users in that role

        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_NAVITEMS,
            $id
        ));
    }

    /**
     * Select all roles
     */
    public function selectAll() {
        if ($this->navEntriesCached == null) {
            $result = array();

            $query = "SELECT * FROM %s WHERE 1 ORDER BY role ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_NAVITEMS));
            while ($row = $this->database->fetchRow()) {
                $role = new ImbaUserRole();
                $role->setHandle($row["handle"]);
                $role->setRole($row["role"]);
                $role->setName($row["name"]);
                $role->setSmf($row["smf"]);
                $role->setWordpress($row["wordpress"]);
                $role->setIcon($row["icon"]);
                $role->setId($row["id"]);

                array_push($result, $role);
            }

            $this->navEntriesCached = $result;
        }

        return $this->navEntriesCached;
    }

    /**
     * Get a new Role
     */
    public function getNew() {
        $role = new ImbaUserRole();
        return $role;
    }

    /**
     * Select one Role by role
     */
    public function selectByRole($roleId) {
        foreach ($this->selectAll() as $role) {
            if ($role->getRole() == $roleId)
                return $role;
        }
        return null;
    }

    /**
     * Select one Role by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $role) {
            if ($role->getId() == $id)
                return $role;
        }
        return null;
    }

    /**
     * Render Portal Navigation
     */
    public function renderPortalNavigation() {
        $topNav = new ImbaTopNavigation();

        /**
         * Set up the portal navigation
         */
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
        $return = "";
        foreach ($topNav->getElements() as $nav) {
            $return .= "<li><a href='" . $topNav->getElementUrl($nav) . "' title='" . $topNav->getElementComment($nav) . "'>" . $topNav->getElementName($nav) . "</a></li>";
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
        return $return;
    }

    public function renderImbaGameNavigation() {
        $return = "<li>";
        $return .= "<a id='imbaMenuImbAdmin' href='javascript:void(0)' onclick='javascript: loadImbaGameDefaultGame();' title='";
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
        return $return;
    }

}

?>
