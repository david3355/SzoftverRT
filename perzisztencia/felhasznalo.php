<?php

require_once("../persistent.php");

class Felhasznalo extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "felhasznalo";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['nev'])) $errors[] = 'NEV_NINCS_MEGADVA';
        if (empty($params['email'])) $errors[] = 'EMAIL_NINCS_MEGADVA';
        if (empty($params['jelszo'])) $errors[] = 'JELSZO_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getFelhasznaloAdatok() {   
        return $this->getFields();   
    }
    
    function setFelhasznaloAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

