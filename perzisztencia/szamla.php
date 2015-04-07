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
        
        
        if (empty($params['sorszam_elotag'])) $errors[] = 'SORSZAM_ELOTAG_NINCS_MEGADVA';
        if (empty($params['sorszam_szam'])) $errors[] = 'SORSZAM_SZAM_NINCS_MEGADVA';
        if (empty($params['kiallito_neve'])) $errors[] = 'KIALLITO_NEVE_NINCS_MEGADVA';
        if (empty($params['kiallito_cim'])) $errors[] = 'KIALLITO_CIM_NINCS_MEGADVA';
        if (empty($params['kiallito_adoszam'])) $errors[] = 'KIALLITO_ADOSZAM_NINCS_MEGADVA';
        if (empty($params['kiallito_bszla'])) $errors[] = 'KIALLITO_BSZLA_NINCS_MEGADVA';
        if (empty($params['befogado_neve'])) $errors[] = 'BEFOGADO_NEVE_NINCS_MEGADVA';
        if (empty($params['begogado_cim'])) $errors[] = 'BEFOGADO_CIM_NINCS_MEGADVA';
        if (empty($params['befogado_adoszam'])) $errors[] = 'BEFOGADO_ADOSZAM_NINCS_MEGADVA';
        if (empty($params['befogado_bszla'])) $errors[] = 'BEFOGADO_BSZLA_NINCS_MEGADVA';
        if (empty($params['fizetesi_mod'])) $errors[] = 'FIZETESI_MOD_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getSzamlaAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

