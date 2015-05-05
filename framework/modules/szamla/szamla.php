<?php

/**
 * Class Szamla
 */
class Szamla extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array &$params = null)
    {

    }

    /**
     * @param array $params
     */
    protected function onAfterCreate(array $params = null)
    {
        // Számlatételek létrehozása

        foreach ($params['tetelek'] as $tetel_adatok) {
            $szamla_id = array('szamla_sorszam_elotag' => $params['sorszam_elotag'], 'szamla_sorszam_szam' => $params['sorszam_szam']);
            $this->pm->createObject('SzamlaTetel', array_merge($tetel_adatok, $szamla_id));
        }
    }

    /**
     * @return bool|int
     */
    protected function onBeforeDelete()
    {
        // Számlatételek törlése
        $szt = new SzamlaTetel();
        $sztetelek = $szt->getFields(['id'], ['szamla_fk' => $this->getID()]);    // a getFields-t úgy kéne megírni, hogy lehessen where feltételt megadni, ha a feltételek egy null array, akkor a where feltételbe az id kerül
        $deleted = 0;
        foreach ($sztetelek as $szt_rekord) {
            $szt_objektum = new SzamlaTetel($szt_rekord['id']);
            $deleted += $szt_objektum->delete();
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
        if (empty($params['kiallito_bszla'])) $errors[] = 'KIALLITO_BSZLA_NINCS_MEGADVA';
        if (empty($params['befogado_neve'])) $errors[] = 'BEFOGADO_NEVE_NINCS_MEGADVA';
        if (empty($params['begogado_cim'])) $errors[] = 'BEFOGADO_CIM_NINCS_MEGADVA';
        if (empty($params['befogado_adoszam'])) $errors[] = 'BEFOGADO_ADOSZAM_NINCS_MEGADVA';
        if (empty($params['befogado_bszla'])) $errors[] = 'BEFOGADO_BSZLA_NINCS_MEGADVA';
        if (empty($params['fizetesi_mod'])) $errors[] = 'FIZETESI_MOD_NINCS_MEGADVA';

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
            /*számlatömb előtag alapján példányosít egy objektumot, erre meghívja a getNextUniqueId("szamla_aktual_szam")
			a visszakapott sorszámot beállítja az új számlának*/
			$szT=new Szamlatomb($adatok['szlatomb_obj_id']);
			$adatok['szla_sorszam']=$szT->getNextUniqueId("szamla_aktual_szam");
			
				return $this->setFields($adatok);
        }

        return $err;
    }
}

