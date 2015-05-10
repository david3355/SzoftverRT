<?php

/**
 * Class PenztarTetel
 */
class PenztarTetel extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params = null)
    {
		//ellenőrzi, hogy van-e fedzet a megadott összegre, ha kiadás (- előjelű)
		if($params['osszeg']<0)
		{
			$penztar=new Penztar($params['penztarID']);
			if($penztar->getFields('egyenleg')<$params['osszeg'])
			{
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

        if (empty($params['penztarID'])) $errors[] = 'PENZTAR_FK_NINCS_MEGADVA';
        if (empty($params['tetel_sorszam'])) $errors[] = 'SORSZAM_NINCS_MEGADVA';
        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';
        if (empty($params['osszeg'])) $errors[] = 'OSSZEG_NINCS_MEGADVA';
        if (empty($params['datum'])) $errors[] = 'DATUM_NINCS_MEGADVA';

        return $errors;
    }

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

    protected static function getOwnParameters() {
        return array('id', 'penztar_fk', 'sorszam', 'megnevezes', 'osszeg', 'datum', 'storno');
    }
}

