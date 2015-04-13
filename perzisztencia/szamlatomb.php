<?php

require_once("../Persistent.php");

class Szamlatomb extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "szamlatomb";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['szamla_elotag'])) $errors[] = 'SZAMLA_ELOTAG_NINCS_MEGADVA';
        if (empty($params['szamla_kezdoszam'])) $errors[] = 'SZAMLA_KEZDOSZAM_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getSzamlatombAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlatombAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

