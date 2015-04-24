<?php

/**
 * Class SzamlaKifizetes
 */
class SzamlaKifizetes extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array &$params = null)
    {
		//fedzetellenőrzés a kifizetésre (összeg - előjelű)
		if($params['osszeg']<0)
		{
			$penztar=new Penztar($params['penztarID']);
			if($penztar->getFields('egyenleg')<$params['osszeg'])
			{
				throw new Exception("NINCS_FEDEZET");
			}
		}
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
}

