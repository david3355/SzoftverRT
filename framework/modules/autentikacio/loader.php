<?php

class AutentikacioLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Autentikacio":
                return $this->myfolder."/autentikacio.php";
            case "AutentikacioKomponens":
                return $this->myfolder."/autentikacio_komponens.php";
            default:
                return null;
        }
    }
}