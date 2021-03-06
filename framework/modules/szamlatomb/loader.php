<?php

class SzamlatombLoader extends AbstractLoader
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
            case "SzamlatombKomponens":
                return $this->myfolder."/szamlatomb_komponens.php";
			case "Szamlatomb_Lazy_Data_Table":
                return $this->myfolder."/szamlatomb_lazy_data_table.php";
            default:
                return null;
        }
    }
}