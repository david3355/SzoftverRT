<?php

class PDF_API_Loader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "PDF_API":
                return $this->myfolder . "/pdf_api.php";
            default:
                return null;
        }
    }
}