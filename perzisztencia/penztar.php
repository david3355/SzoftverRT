<?php

require_once("../persistent.php");

class Penztar extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "penztar";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        return $errors;
    }
    
    function getPenztarAdatok() {   
        return $this->getFields();   
    }
    
    function setPenztarAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

