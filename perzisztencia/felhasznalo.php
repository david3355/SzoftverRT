<?php

require_once("../Persistent.php");

class Felhasznalo extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        
    }

    protected function onBeforeDelete() {

    }

    protected static function getTableName() {
        return "felhasznalo";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['nev'])) $errors[] = 'NEV_NINCS_MEGADVA';
		if (empty($params['email'])) $errors[] = 'EMAIL_NINCS_MEGADVA';
        
		/*user_nev:
			-nem üres
			-egyedi
			-min. 3 hosszú
			-strtolower*/
		$nev_strtolower=strtolower($params['user_nev']);
		if(!empty($nev_strtolower))
		{
			if(strlen($nev_strtolower)>=3)
			{
				//nem egyedi, mert már van ilyen user_nev
				if($this->db->query("SELECT * FROM {$this->getTableName()} WHERE user_nev = '{$nev_strtolower}'") == true)
				{
					$errors[]="USED_USER_NEV";
				}
			}
			else
			{
				$errors[]="ROVID_USER_NEV";
			}
		}
		else
		{
			$errors[]="USERNEV_NINCS_MEGADVA";
		}
		
		/*jelszo:
			-nem üres
			-min. 6 hosszú
			-kis-nagy betű, szám*/
		if(!empty($params['jelszo']))
		{
			if(strlen($params['jelszo'])>=6)
			{
				if(!isPasswordSecure($params['jelszo']))
				{
                    $errors[]="KIS_BETU_NAGY_BETU_SZAM_SZUKSEGES";
				}
			}
			else
			{
				$errors[]="ROVID_JELSZO";
			}
		}
		else
		{
			$errors[]="JELSZO_NINCS_MEGADVA";
		}
		
		
        return $errors;
    }

    private function isPasswordSecure($pwd)
    {
        return preg_match('/[A-Z]+/', $pwd) && preg_match('/[0-9]+/', $pwd) && preg_match('/[a-z]+/', $pwd);
    }
    
    function getFelhasznaloAdatok() {   
        return $this->getFields();   
    }
    
    function setFelhasznaloAdatok(array $adatok) {
        $this->setFields($adatok);
    }
}

