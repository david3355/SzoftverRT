<?php

require_once("../Persistent.php");

class Szamla extends Persistent
{
    /**
     * @param array $params
     */
    protected function onAfterCreate(array $params = null) {
        // Számlatételek létrehozása

        foreach ($params['tetelek'] as $tetel_adatok) {
            $szamla_id = array('szamla_sorszam_elotag' => $params['sorszam_elotag'], 'szamla_sorszam_szam' => $params['sorszam_szam']);
            $this->pm->createObject('SzamlaTetel', array_merge($tetel_adatok, $szamla_id));
        }
    }

    protected function onBeforeDelete() {
        // Számlatételek törlése
        $szt_table = SzamlaTetel::getTableName();   // A getTableName-nek publicnak kéne lennie, illetve kérdés, hogy ez a megoldás marad-e
        $sql = sprintf("SELECT sorszam_elotag, sorszam_szam FROM %s WHERE id=%s", self::getTableName(), $this->getID());
        $sorszam = $this->db->query($sql);
        $sql = sprintf("DELETE FROM %s WHERE szamla_sorszam_elotag=%s AND szamla_sorszam_szam=%s", $szt_table, $sorszam[0]['sorszam_elotag'], $sorszam[0]['sorszam_szam']);
        return $this->db->query($sql);
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
	
	//megadja a kapott számla előtag következő sorszámát
	public function getNextSzlaID()
	{
		$res = $this->db->query("SELECT * FROM {$this->getTableName()} WHERE sorszam_elotag = '{$this->getFields('sorszam_elotag')}' ORDER BY sorszam_szam DESC LIMIT 1");
		$nextid=$res['sorszam_szam']+1;
		return $nextid;
	}
}

