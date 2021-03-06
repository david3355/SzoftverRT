<?php

/**
 * Class SzamlaKifizetes
 */
class Kifizetes extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params)
    {
        //fedzetellenőrzés a kifizetésre (összeg - előjelű)
        if ($params['osszeg'] < 0) {
            $penztar = new Penztar($params['penztarID']);
            if ($penztar->getFields('egyenleg') < $params['osszeg']) {
                throw new Exception("NINCS_FEDEZET");
            }
        }

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

        if (empty($params['kifizetes_datum'])) $errors[] = 'KIFIZETES_DATUM_NINCS_MEGADVA';
        if (empty($params['osszeg'])) $errors[] = 'OSSZEG_NINCS_MEGADVA';

        return $errors;
    }

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setSzamlaKifizetesAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }

        return $err;
    }

    function getSzamlaKifizetesAdatok()
    {
        return $this->getFields(array('id', 'kifizetes_datum', 'osszeg', 'szamla_fk'));
    }
    
    /**
     *  Minden osztály a saját paramétereit adja vissza az összes paraméter közül
     * @param array $params
     * @return mixed array
     */
    protected static function getOwnParameters() {
        return array('id', 'kifizetes_datum', 'osszeg', 'szamla_fk');
    }
}

