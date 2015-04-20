<?php

/**
 * Class PenztarTetel
 */
class PenztarTetel extends Persistent
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

        if (empty($params['penztarID'])) $errors[] = 'PENZTAR_FK_NINCS_MEGADVA';
        if (empty($params['tetel_sorszam'])) $errors[] = 'SORSZAM_NINCS_MEGADVA';
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['osszeg'])) $errors[] = 'OSSZEG_NINCS_MEGADVA';
        if (empty($params['datum'])) $errors[] = 'DATUM_NINCS_MEGADVA';

        return $errors;
    }

    /*function getPenztarTetelAdatok() {   
        return $this->getFields();   
    }*/

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setPenztarTetelAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }

        return $err;
    }

    /*a kapott pénztárhoz tartozó következő tételszámot adja meg
        -a pénztár tétel minden pénztár_id -vel egyedi, azaz pl. 47-es tételből több is lehet, de
        példa: 4-es pénztárhoz 45-ös tétel -ből csak 1!
    public function getNextPenztarTetelID()
    {
        $this->db->query("SELECT * FROM {$this->getTableName()} WHERE penztarID = '{$this->getFields('penztarID')}' ORDER BY tetel_sorszam DESC LIMIT 1");
        $nextid=$res['tetel_sorszam']+1;
        return $nextid;
    }*/
}

