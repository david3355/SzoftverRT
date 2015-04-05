<?php

require_once("../persistent.php");

class SzamlaTetel extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "szamla_tetel";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getSzamlaTetelAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaTetelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

