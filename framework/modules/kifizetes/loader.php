<?php

/**
 * Created by PhpStorm.
 * User: Gábor
 * Date: 2015.05.03.
 * Time: 17:15
 */
class KifizetesLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Kifizetes":
                return $this->myfolder . "/kifizetes.php";
            case "KifizetesKomponens":
                return $this->myfolder . "/kifizetes_komponens.php";
            case "Kifizetes_Lazy_Data_Table":
                return $this->myfolder."/kifizetes_lazy_data_table.php";
            case "Kifizetes_Globalis":
                return $this->myfolder."/kifizetes_globalis.php";
            default:
                return null;
        }
    }
}