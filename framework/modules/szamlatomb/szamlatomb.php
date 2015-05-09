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
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }
        return $err;
    }

    function setLezaras($close)
    {
        if($close){
            return $this->setFields(array('lezaras_datum'=>date('Y-m-d')));
        } else {
            return $this->setFields(array('lezaras_datum'=>null));
        }
    }

    function getSzamlatombAdatok()
    {
        return $this->getFields(array('id', 'megnevezes', 'szamla_elotag', 'szamla_aktual_szam', 'lezaras_datum'));
    }
    
    protected static function getOwnParameters() {
        return array('id', 'megnevezes', 'szamla_elotag', 'szamla_aktual_szam', 'lezaras_datum');
    }
}

