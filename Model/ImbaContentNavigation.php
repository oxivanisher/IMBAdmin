<?php

/**
 * Base class for all navigations
 */
class ImbaContentNavigation {

    private $Options = array();

    public function getElements() {
        $elements = array();
        foreach ($this->Options as $Option) {
            array_push($elements, $Option->getIdentifier());
        }
        return $elements;
    }

    public function addElement($Identifier, $Name) {
        $newElement = new ImbaContentNavigationOption();
        $newElement->setName($Name);
        $newElement->setIdentifier($Identifier);
        array_push($this->Options, $newElement);
    }

    public function getElementName($Identifier){
        foreach ($this->Options as $Option) {
            if ($Option->getIdentifier() == $Identifier) {
                return $Option->getName();
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

}

?>