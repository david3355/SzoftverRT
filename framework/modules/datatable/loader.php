<?php

class DataTableLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Abstract_Lazy_Data_Table":
                return $this->myfolder."/lazy_data_table.php";
            default:
                return null;
        }
    }
}

