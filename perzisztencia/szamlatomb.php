<?php

require_once("../persistent.php");

class Szamlatomb extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "szamlatomb";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getSzamlatombAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlatombAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

