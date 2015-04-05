<?php

require_once("../persistent.php");

class PenztarTetel extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "penztar_tetel";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getPenztarTetelAdatok() {   
        return $this->getFields();   
    }
    
    function setPenztarTetelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

