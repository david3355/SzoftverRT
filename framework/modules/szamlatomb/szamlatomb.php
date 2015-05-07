<?php

/**
 * Class Szamlatomb
 */
class Szamlatomb extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params)
    {
        return $params;
    }

    /**
     * @param array $params
     */
    protected function onAfterCreate(array $params = null)
    {

    }

    /**
     *
     */
    protected function onBeforeDelete()
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function validate(array $params = null)
    {
        $errors = array();

        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['szamla_elotag'])) $errors[] = 'SZAMLA_ELOTAG_NINCS_MEGADVA';
        if (empty($params['szamla_aktual_szam'])) $errors[] = 'SZAMLA_KEZDOSZAM_NINCS_MEGADVA';

        return $errors;
    }
	
    /**
     * @param array $adatok
     * @return array|bool
     */
    function setSzamlatombAdatok(array $adatok)
    {
		/*ITT FIGYELNI KELL RÁ, HOGY NE LEHESSEN FELÜLÍRNI A 'SZAMLA_AKTUAL_SZAM' MEZŐT*/
		$errors = array();

		if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
		if (empty($params['szamla_elotag'])) $errors[] = 'SZAMLA_ELOTAG_NINCS_MEGADVA';

		return $errors;
    }

    protected static function getOwnParameters() {
        return array('id', 'megnevezes', 'szamla_elotag', 'szamla_aktual_szam', 'lezaras_datum');
    }
}

