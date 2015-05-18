<?php

class KifizetesGlobalis extends Persistent {

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
     * A(z opcionálisan) módosított paraméterekkel tér vissza
     */
    protected function onBeforeCreate(array $params)
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
}