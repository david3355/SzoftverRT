<?php

/**
 * Class Szamla
 */
class Szamla extends Persistent
{

    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params)
    {
        /*számlatömb előtag alapján példányosít egy objektumot, erre meghívja a getNextUniqueId("szamla_aktual_szam")
        a visszakapott sorszámot beállítja az új számlának*/
        $szT=$this->pm->getObject($params['szlatomb_obj_id']);
        $params['szla_sorszam']=$szT->getSzamlatombAdatok()['szamla_elotag']."/".$szT->getNextUniqueId('szamla_aktual_szam', $params['szlatomb_obj_id']);
		//throw new Exception($params['szla_sorszam']."i");
        return $params;
    }

    /**
     * @param array $params
     */
    protected function onAfterCreate(array $params = null)
    {
        $szamla_fk = array('szamla_fk' => $this->getID());
        // Számlatételek létrehozása
        foreach ($params['tetelek'] as $tetel_adatok) {
            $err = $this->pm->createObject('SzamlaTetel', array_merge($tetel_adatok, $szamla_fk));
        }
    }

    /**
     * @return bool|int
     */
    protected function onBeforeDelete()
    {
        // Számlatételek törlése
        $sztetelek = $this->pm->select('SzamlaTetel', ['id'])->where('szamla_fk', '=', $this->getID)->exeSelect();
        $deleted = 0;
        foreach($sztetelek as $sztetel)
        {
            $deleted += $this->pm->getObject($sztetel['id'])->delete();
        }
        return $deleted;
    }

    /**
     * @param array $params
     * @return array
     */
    public function validate(array $params = null)
    {
        $errors = array();
		
        if (empty($params['szlatomb_obj_id'])) $errors[] = 'NINCS_SZLA_TOMB';
        if (empty($params['kiallito_neve'])) $errors[] = 'KIALLITO_NEVE_NINCS_MEGADVA';
        if (empty($params['kiallito_cim'])) $errors[] = 'KIALLITO_CIM_NINCS_MEGADVA';
        if (empty($params['kiallito_adoszam'])) $errors[] = 'KIALLITO_ADOSZAM_NINCS_MEGADVA';
        //if (empty($params['kiallito_bszla'])) $errors[] = 'KIALLITO_BSZLA_NINCS_MEGADVA';
        if (empty($params['befogado_nev'])) $errors[] = 'BEFOGADO_NEVE_NINCS_MEGADVA';
        if (empty($params['befogado_cim'])) $errors[] = 'BEFOGADO_CIM_NINCS_MEGADVA';
        //if (empty($params['befogado_adoszam'])) $errors[] = 'BEFOGADO_ADOSZAM_NINCS_MEGADVA';
        //if (empty($params['befogado_bszla'])) $errors[] = 'BEFOGADO_BSZLA_NINCS_MEGADVA';
        if (empty($params['fizetesi_mod'])) $errors[] = 'FIZETESI_MOD_NINCS_MEGADVA';
        if (empty($params['tetelek'])) $errors[] = 'EGY_TETEL_SINCS_NINCS_MEGADVA';

        return $errors;
    }

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setSzamlaAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if(empty($err))
		{
            return $this->setFields($adatok);
        }

        echo $err;
    }

    function getSzamlaAdatok()
    {
        $szamla = $this->getFields();
        $sztdata = $this->pm->select('SzamlaTetel', ['id'])->where('szamla_fk', '=', $this->getID())->exeSelect();
        $szamlatetelek = array();
        foreach($sztdata as $szt)
        {
            $szamlatetelek[] = $this->pm->getObject($szt['id'])->getSzamlaTetelAdatok();
        }
        $szamla['tetelek']= $szamlatetelek;;
        return $szamla;
    }

    protected static function getOwnParameters() {
        return array('id', 'szlatomb_obj_id', 'szla_sorszam', 'kiallito_neve', 'kiallito_cim', 'kiallito_adoszam', 'kiallito_bszla', 'befogado_nev', 'befogado_cim', 'befogado_adoszam', 'befogado_bszla', 'fizetesi_mod', 'kiallitas_datum', 'teljesites_datum', 'fizetes_datum', 'megjegyzes');
    }
}

