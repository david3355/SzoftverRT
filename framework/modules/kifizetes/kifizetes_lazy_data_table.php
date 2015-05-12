<?php

class Kifizetes_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    protected function getData(array $post = null)
    {
        $this->numberOfAllRows = $this->pm->select('Kifizetes',['count(*) as rn'])->exeSelect()[0]['rn'];
        
        $this->pm->select('Kifizetes');
        if(!empty($this->selectedSortColumn['column']) && !empty($this->selectedSortColumn['dest'])){
            $this->pm->orderBy($this->selectedSortColumn['column'],$this->selectedSortColumn['dest']);
        }
        if($this->selectedPageNumber == 1 || $this->selectedStep == 1){
            $this->pm->limit($this->selectedPageNumber-1,$this->selectedStep);
        } else {
            $this->pm->limit($this->selectedPageNumber,$this->selectedStep);
        }
        
        $ret = array();
        
        foreach($this->pm->exeSelect() as $kifizetes){
            $szamla = $this->pm->getObject($kifizetes['szamla_fk']);
            $szamleFields = $szamla->getSzamlaAdatok();
            array_push($ret, array("id" => $kifizetes['id'],
                                   "kifizetes_datum" => $kifizetes['kifizetes_datum'],
                                   "osszeg" => $kifizetes['osszeg']." Ft",
                                   "szamla_sorszam" => $szamleFields['sorszam_elotag'].$szamleFields['sorszam_szam']));
        }
        
        return $ret;
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'szamlakifizetes-datatable';

        $this->dataColumns = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "kifizetes_datum" => array(
                'name' => 'Kifizetés dátum',
                'sortable' => true
            ),
            "osszeg" => array(
                'name' => 'Összeg',
                'sortable' => true
            ),
            "szamla_sorszam" => array(
                'name' => 'Számla sorszám',
                'sortable' => false
            )
        );
        $this->operationColumns = array(
            array(
                'name' => 'edit',
                'text' => 'Szerkesztés'
            ),
            array(
                'name' => 'delete',
                'text' => 'Törlés'
            )
        );                                 
    }
}