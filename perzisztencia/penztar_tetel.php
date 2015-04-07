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
        
        if (empty($params['penztar_fk'])) $errors[] = 'PENZTAR_FK_NINCS_MEGADVA';
        if (empty($params['sorszam'])) $errors[] = 'SORSZAM_NINCS_MEGADVA';
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['osszeg'])) $errors[] = 'OSSZEG_NINCS_MEGADVA';
        if (empty($params['datum'])) $errors[] = 'DATUM_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getPenztarTetelAdatok() {   
        return $this->getFields();   
    }
    
    function setPenztarTetelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

