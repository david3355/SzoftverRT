<?php

/**
 * Class Ugyfel
 */
class Ugyfel extends Persistent
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

        if (empty($params['nev'])) $errors[] = 'NEV_NINCS_MEGADVA';
        if (empty($params['cim_irszam'])) $errors[] = 'CIM_IRSZAM_NINCS_MEGADVA';
        if (empty($params['cim_varos'])) $errors[] = 'CIM_VAROS_NINCS_MEGADVA';
        if (empty($params['cim_utca_hsz'])) $errors[] = 'CIM_UTCA_HSZ_NINCS_MEGADVA';

        /*azonosító:
			-nem üres
			-csak szám
			-10karakter
			-nincs 4 azonos karakter egymás után*/
        if (empty($params['azonosito'])) $errors[] = "AZONOSITO_NINCS_MEGADVA";

        if (!preg_match('/^[0-9]*$/', $params['azonosito'])) $errors[] = "CSAK_SZAM_AZONOSITO";

        if (strlen($params['azonosito']) != 10) $errors[] = "AZONOSITO_HOSSZ_HIBAS";

        if($this->hasRepeat($params['azonosito'])) $errors[] = "AZONOSITO_TUL_SOK_ISMETLODES";

        return $errors;
    }

    function hasRepeat($maxEnabledRepeat = 3)
    {
        return false;
    }

    /**
     * @return bool
     */
    function deleteUgyfel()
    {
        return $this->delete();
    }

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setUgyfelAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }
        return $err;
    }

    protected static function getOwnParameters() {
        return array('id', 'azonosito', 'nev', 'cim_irszam', 'cim_varos', 'cim_utca_hsz', 'telefon', 'email');
    }
}

