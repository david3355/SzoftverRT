<?php

class PersistentLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "PersistenceManager":
                return $this->myfolder."/persistence_manager.php";
            case "Persistent":
                return $this->myfolder."/persistent.php";
            default:
                return null;
        }
    }
}