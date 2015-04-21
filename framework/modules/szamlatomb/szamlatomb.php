<?php

/**
 * Class Szamlatomb
 */
class Szamlatomb extends Persistent
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

    /**
     * @param array $params
     * @return array
     */
    public function validate(array $params = null)
    {
        $errors = array();

        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['szamla_elotag'])) $errors[] = 'SZAMLA_ELOTAG_NINCS_MEGADVA';
        if (empty($params['szamla_kezdoszam'])) $errors[] = 'SZAMLA_KEZDOSZAM_NINCS_MEGADVA';

        return $errors;
    }

    /*function getSzamlatombAdatok() {   
        return $this->getFields();   
    }*/

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

    /*
    public function getNextSzlaID()
    {
        //return ;
    }
    */
}

