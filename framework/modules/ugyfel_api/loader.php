<?php


class Ugyfel_API_Loader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Ugyfel_API":
                return $this->myfolder . "/ugyfel_api.php";
            default:
                return null;
        }
    }
}