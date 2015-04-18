<?php

require_once('autoload.php');

class Penztar extends Persistent
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
        
        return $errors;
    }
    
    /*function getPenztarAdatok() {   
        return $this->getFields();   
    }*/
    
    function setPenztarAdatok(array $adatok) {
        $err=$this->validate($adatok);
		if(empty($err))
		{
			$this->setFields($adatok);
		}
    }
}

