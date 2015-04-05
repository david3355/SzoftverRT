<?php

require_once("../persistent.php");

class SzamlaKifizetes extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "szamla_kifizetes";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getSzamlaKifizetesAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaKifizetesAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

