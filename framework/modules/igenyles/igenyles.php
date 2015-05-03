<?php

class Igenyles extends Persistent{

    /**
     * return hiba kódok array
     *
     * Létrehozási/módosítási paraméterek ellenőrzése
     * Alosztály implementálja
     */
    function validate(array $params = null)
    {
        // TODO: Implement validate() method.
    }

    /**
     * return void
     *
     * Tetszőleges létrehozási tevékenység.
     * Alosztály implementálja
     */
    protected function onAfterCreate(array $params = null)
    {
        // TODO: Implement onAfterCreate() method.
    }

    /**
     * Az objektum létrehozása előtt lehetőség van a paraméterek módosítására, ellenőrzésére
     */
    protected function onBeforeCreate(array &$params = null)
    {
        // TODO: Implement onBeforeCreate() method.
    }

    /**
     *  Mielőtt az objektumot kitöröljük az adatbázisból, a kompozícióval hozzákapcsolt gyerekobjektumokat is törölni kell
     *  Csak utána törölhetjük az ősobjektumot
     *  return bool
     */
    protected function onBeforeDelete()
    {
        // TODO: Implement onBeforeDelete() method.
    }

    /**
     *  Minden osztály a saját paramétereit adja vissza az összes paraméter közül
     * @param array $params
     * @return mixed array
     */
    protected static function getOwnParameters(array $params = null)
    {
        // TODO: Implement getOwnParameters() method.
    }
}