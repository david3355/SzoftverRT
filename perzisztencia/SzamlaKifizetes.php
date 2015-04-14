<?php

require_once('autoload.php');

class SzamlaKifizetes extends Persistent
{
    protected function onBeforeCreate(array &$params = null) {

    }

    protected function onAfterCreate(array $params = null) {
        
    }

    protected function onBeforeDelete() {

    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['kifizetes_datum'])) $errors[] = 'KIFIZETES_DATUM_NINCS_MEGADVA';
        if (empty($params['osszeg'])) $errors[] = 'OSSZEG_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getSzamlaKifizetesAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaKifizetesAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

