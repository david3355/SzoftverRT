<?php

class FelhasznaloLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Felhasznalo":
                return $this->myfolder."/felhasznalo.php";
            case "FelhasznaloKomponens":
                return $this->myfolder."/felhasznalo_komponens.php";
            case "Felhasznalo_Lazy_Data_Table":
                return $this->myfolder."/felhasznalo_lazy_data_table.php";
            default:
                return null;
        }
    }
}