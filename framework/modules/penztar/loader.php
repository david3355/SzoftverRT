<?php

class PenztarLoader extends AbstractLoader
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
            case "PenztarKomponens":
                return $this->myfolder."/penztar_komponens.php";
            case "PenztarTetel":
                return $this->myfolder."/penztar_tetel.php";
            default:
                return null;
        }
    }
}