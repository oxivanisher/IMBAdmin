<?php

/**
 * FIXME: No integration of user roles for security!
 */

/**
 * Base class for all navigations
 */
class ImbaContentNavigation {

    private $Name = null;
    private $Options = array();
    private $ShowLoggedIn = false;
    private $ShowLoggedOff = false;
    private $MinUserRole = 99;
    private $Comment = null;

    public function getName() {
        return $this->Name;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function getShowLoggedIn() {
        return $this->ShowLoggedIn;
    }

    public function setShowLoggedIn($ShowLoggedIn) {
        $this->ShowLoggedIn = $ShowLoggedIn;
    }

    public function getShowLoggedOff() {
        return $this->ShowLoggedOff;
    }

    public function setShowLoggedOff($ShowLoggedOff) {
        $this->ShowLoggedOff = $ShowLoggedOff;
    }

    public function getMinUserRole() {
        return $this->MinUserRole;
    }

    public function setMinUserRole($MinUserRole) {
        $this->MinUserRole = $MinUserRole;
    }

    public function getComment() {
        return $this->Comment;
    }

    public function setComment($Comment) {
        $this->Comment = $Comment;
    }

    public function getElements() {
        $elements = array();
        foreach ($this->Options as $Option) {
            array_push($elements, $Option->getIdentifier());
        }
        return $elements;
    }

    public function addElement($Identifier, $Name, $Comment) {
        $newElement = new ImbaContentNavigationOption();
        $newElement->setName($Name);
        $newElement->setComment($Comment);
        $newElement->setIdentifier($Identifier);
        array_push($this->Options, $newElement);
    }

    public function getElementName($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getName();
            }
        }
    }

    public function getElementComment($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getComment();
            }
        }
    }

    public function getElement($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->get();
            }
        }
    }

}

/**
 * Class for navigation options
 */
class ImbaContentNavigationOption {

    /**
     * Fields for class ImbaContentNavigationOption
     */
    protected $Identifier = null;
    protected $Name = null;
    protected $Comment = null;

    public function getIdentifier() {
        return $this->Identifier;
    }

    public function setIdentifier($Identifier) {
        $this->Identifier = $Identifier;
    }

    public function getName() {
        return $this->Name;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function getComment() {
        return $this->Comment;
    }

    public function setComment($Comment) {
        $this->Comment = $Comment;
    }

    public function get() {
        return $this;
    }

}

/**
 * Top navigation class
 */
class ImbaTopNavigationOption Extends ImbaContentNavigationOption {

    protected $Target = null;
    protected $URL = null;
    protected $Name = null;
    protected $Comment = null;

    public function getTarget() {
        return $this->Target;
    }

    public function setTarget($Target) {
        $this->Target = $Target;
    }

    public function getUrl() {
        return $this->URL;
    }

    public function setUrl($Url) {
        $this->URL = $Url;
    }

    public function getName() {
        return $this->Name;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function getComment() {
        return $this->Comment;
    }

    public function setComment($Comment) {
        $this->Comment = $Comment;
    }

}

/**
 * class for top navigation
 */
class ImbaTopNavigation Extends ImbaContentNavigation {

    private $Options = array();

    public function getElements() {
        $elements = array();
        foreach ($this->Options as $Option) {
            array_push($elements, $Option->getIdentifier());
        }
        return $elements;
    }

    public function addElement($Identifier, $Name, $Target, $Url, $Comment) {
        $newElement = new ImbaTopNavigationOption();
        $newElement->setName($Name);
        $newElement->setIdentifier($Identifier);
        $newElement->setTarget($Target);
        $newElement->setUrl($Url);
        $newElement->setComment($Comment);
        array_push($this->Options, $newElement);
    }

    /*
     * this is probably not needed ... lol
     *
    public function getElementIdentifier($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getIdentifier();
            }
        }
    }
     * 
     */

    public function getElementName($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getName();
            }
        }
    }

    public function getElementTarget($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getTarget();
            }
        }
    }

    public function getElementUrl($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getUrl();
            }
        }
    }

    public function getElementComment($Identifier) {
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getComment();
            }
        }
    }

}

?>