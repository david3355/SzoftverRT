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

        /*azonosító:
			-nem üres
			-csak szám
			-10karakter
			-nincs 4 azonos karakter egymás után*/
		if(!empty($params['azonosito']))
		{
			if(preg_match('/^[0-9]*$/', $params['azonosito']))
			{
				if(strlen($params['azonosito'])!=10)
				{
					//IDE KELL MÉG
				}
				else
				{
					$errors[]="AZONOSITO_HOSSZ_HIBAS";
				}
			}
			else
			{
				$errors[]="CSAK_SZAM_AZONOSITO";
			}
		}
		else
		{
			$errors[]="AZONOSITO_NINCS_MEGADVA";
		}
		
        return $errors;
    }
    
    function deleteUgyfel(){
        return $this->delete();
    }
    
    function getUgyfelAdatok() {   
        return $this->getFields();   
    }
    
    function setUgyfelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

