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
        $params['azonosito'] = $this->genUgyfelAzon();
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
			-GENERÁLT
			-csak szám
			-10karakter
            -egyedi
			-nincs 4 azonos karakter egymás után*/

        return $errors;
    }

    function idExists($id)
    {
        $result = PersistenceManager::getInstance()->select('Ugyfel')->where('azonosito', '=', $id)->exeSelect();
        return !empty($result);
    }

    private function genUgyfelAzon()
    {
        do {
            $id = "";
            for ($i = 0; $i < 10; $i++) {
                do {
                    $gen = rand(0, 10);
                } while (i > 2 && $id[i - 1] == $id[i - 2] && $id[i - 1] == $id[i - 3] && $id[i - 1] == $gen);
                $id .= $gen;
            }
        }while($this->idExists($id));

        return $id;
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

    function getUgyfelAdatok()
    {
        return $this->getFields(array('id', 'azonosito', 'nev', 'cim_irszam', 'cim_varos', 'cim_utca_hsz', 'telefon', 'email'));
    }

    protected static function getOwnParameters() {
        return array('id', 'azonosito', 'nev', 'cim_irszam', 'cim_varos', 'cim_utca_hsz', 'telefon', 'email');
    }
}

