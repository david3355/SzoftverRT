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
            case "Szamlatomb":
                return $this->myfolder."/szamlatomb.php";
            default:
                return null;
        }
    }
}