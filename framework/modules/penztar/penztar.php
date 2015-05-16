<?php

/**
 * Class Penztar
 */
class Penztar extends Persistent
{
    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params)
    {
        $params['egyenleg'] = 0;
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
        $penztarTetels = $this->pm->select('PenztarTetel')->where('penztar_fk', '=', $this->getID())->exeSelect();
        foreach ($penztarTetels as $penztarTetel){
            $this->pm->getObject($penztarTetel['id'])->delete();
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function validate(array $params = null)
    {
        $errors = array();

        if (empty($params['megnevezes'])) $errors[] = 'MEGNEVEZES_NINCS_MEGADVA';

        return $errors;
    }

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setPenztarAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }
        return $err;
    }

    function getPenztarAdatok()
    {
        return $this->getFields();
    }

    protected static function getOwnParameters() {
        return array('id', 'megnevezes', 'egyenleg');
    }
    
    public function addOsszeg($osszeg){
        $actualEgyenleg = $this->getFields(array('egyenleg'))['egyenleg'];
        if(is_numeric($osszeg)){
            $this->setFields(array('egyenleg' => $actualEgyenleg + $osszeg));
        }
    }
}

