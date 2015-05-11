<?php

class IgenylesLoader extends AbstractLoader
{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        switch ($classname) {
            case "Igenylestomb":
                return $this->myfolder . "/igenyles.php";
            
			case "IgenylesKomponens":
                return $this->myfolder . "/igenyles_komponens.php";
				
			case "Igenyles_Lazy_Data_Table":
                return $this->myfolder . "/igenyles_lazy_data_table.php";
            
			default:
                return null;
        }
    }
}