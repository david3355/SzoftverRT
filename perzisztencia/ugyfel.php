<?php

require_once("../persistent.php");

class Ugyfel extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected static function getTableName() {
        return "ugyfel";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['nev'])) $errors[] = 'NEV_NINCS_MEGADVA';
        if (empty($params['cim_irszam'])) $errors[] = 'CIM_IRSZAM_NINCS_MEGADVA';
        if (empty($params['cim_varos'])) $errors[] = 'CIM_VAROS_NINCS_MEGADVA';
        if (empty($params['cim_utca_hsz'])) $errors[] = 'CIM_UTCA_HSZ_NINCS_MEGADVA';
        if (empty($params['telefon'])) $errors[] = 'TELEFON_NINCS_MEGADVA';
        if (empty($params['email'])) $errors[] = 'EMAIL_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getUgyfelAdatok() {   
        return $this->getFields();   
    }
    
    function setUgyfelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

