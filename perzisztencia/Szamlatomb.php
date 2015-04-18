<?php

require_once('autoload.php');

class Szamlatomb extends Persistent
{
    protected function onBeforeCreate(array &$params = null) {

    }

    protected function onAfterCreate(array $params = null) {
        
    }

    protected function onBeforeDelete() {

    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['szamla_elotag'])) $errors[] = 'SZAMLA_ELOTAG_NINCS_MEGADVA';
        if (empty($params['szamla_kezdoszam'])) $errors[] = 'SZAMLA_KEZDOSZAM_NINCS_MEGADVA';
        
        return $errors;
    }
    
    /*function getSzamlatombAdatok() {   
        return $this->getFields();   
    }*/
    
    function setSzamlatombAdatok(array $adatok) {
        $err=$this->validate($adatok);
		if(empty($err))
		{
			$this->setFields($adatok);
		}
    }
	
	public function getNextSzlaID()
	{
		//return ;
	}
}

