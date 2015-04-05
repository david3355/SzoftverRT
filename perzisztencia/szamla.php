<?php

require_once("../persistent.php");

class Szamla extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "szamla";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getSzamlaAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

