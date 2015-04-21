<?php

class SzamlaLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Szamla":
                return $this->myfolder."/szamla.php";
            case "SzamlaKomponens":
                return $this->myfolder."/szamla_komponens.php";
            case "SzamlaKifizetes":
                return $this->myfolder."/szamla_kifizetes.php";
            case "SzamlaTetel":
                return $this->myfolder."/szamla_tetel.php";
            default:
                return null;
        }
    }
}