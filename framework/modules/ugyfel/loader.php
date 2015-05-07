<?php

class UgyfelLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Ugyfel":
                return $this->myfolder."/ugyfel.php";
            case "UgyfelKomponens":
                return $this->myfolder."/ugyfel_komponens.php";
            case "Ugyfel_Lazy_Data_Table":
                return $this->myfolder."/ugyfel_lazy_data_table.php";
			default:
                return null;
        }
    }
}