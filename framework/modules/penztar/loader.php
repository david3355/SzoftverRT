<?php

class Loader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Penztar":
                return $this->myfolder."/penztar.php";
            case "PenztarTetel":
                return $this->myfolder."/penztar_tetel.php";
            default:
                return null;
        }
    }
}