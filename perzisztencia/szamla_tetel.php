<?php

require_once("../persistent.php");

class SzamlaTetel extends Persistent
{
    protected function onAfterCreate(array $params = null) {
        // Foreign key constraint megadása a sorszam_elotag és sorszam_szam attribútumokra
    }

    protected function onBeforeDelete() {

    }

    protected static function getTableName() {
        return "szamlatetel";
    }

    public function validate(array $params = null) {
        $errors = array();
        
        if (empty($params['szamla_sorszam_elotag'])) $errors[] = 'SZAMLA_FK_NINCS_MEGADVA';
		if (empty($params['szamla_sorszam_szam'])) $errors[] = 'SZAMLA_FK_NINCS_MEGADVA';
        if (empty($params['vamtarifaszam'])) $errors[] = 'VAMTARIFASZAM_NINCS_MEGADVA';
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['mennyiseg_egyseg'])) $errors[] = 'MENNYISEG_EGYSEG_NINCS_MEGADVA';
        if (empty($params['menniyseg'])) $errors[] = 'MENNYISEG_NINCS_MEGADVA';
        if (empty($params['afa'])) $errors[] = 'AFA_NINCS_MEGADVA';
        if (empty($params['netto_ar'])) $errors[] = 'NETTO_AR_NINCS_MEGADVA';
        if (empty($params['brutto_ar'])) $errors[] = 'BRUTTO_AR_NINCS_MEGADVA';
        
        return $errors;
    }
    
    function getSzamlaTetelAdatok() {   
        return $this->getFields();   
    }
    
    function setSzamlaTetelAdatok(array $adatok) {
        $this->setFields($adatok);
    }
	
	/*a kapott számlához tartozó következő tételszámot adja meg
		példa: A-7/45-4*/
	public function getNextSzlaTetelID()
	{
		$this->db->query("SELECT * FROM {$this->getTableName()} WHERE szamla_sorszam_elotag = '{$this->getFields('szamla_sorszam_elotag')}' AND szamla_sorszam_szam = '{$this->getFields('szamla_sorszam_szam')}' ORDER BY sorszam DESC LIMIT 1");
		$nextid=$res['sorszam']+1;
		return $nextid;
	}
}

