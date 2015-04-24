<?php

/**
 * Class SzamlaTetel
 */
class SzamlaTetel extends Persistent
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

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setSzamlaTetelAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }

        return $err;
    }
}

