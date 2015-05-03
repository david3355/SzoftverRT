<?php

/**
 * Class Ugyfel
 */
class Ugyfel extends Persistent
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

    }

    /**
     *
     */
    protected function onBeforeDelete()
    {

    }
	
	public function getOwnParameters(array $params = NULL)
	{
		return true;
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
        if (!empty($params['azonosito'])) {
            if (preg_match('/^[0-9]*$/', $params['azonosito'])) {
                if (strlen($params['azonosito']) != 10) {
                    //IDE KELL MÉG
                } else {
                    $errors[] = "AZONOSITO_HOSSZ_HIBAS";
                }
            } else {
                $errors[] = "CSAK_SZAM_AZONOSITO";
            }
        } else {
            $errors[] = "AZONOSITO_NINCS_MEGADVA";
        }

        return $errors;
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
}

