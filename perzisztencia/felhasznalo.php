<?php

require_once("../persistent.php");

class Felhasznalo extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "felhasznalok";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getFelhasznaloAdatok() {   
        return $this->getFields();   
    }
    
    function setFelhasznaloAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

