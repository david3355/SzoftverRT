<?php

class Szamlatomb_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    private $pm;
	
	protected function getData(array $post = null)
    {
        $this->numberOfAllRows = $this->pm->select('Szamlatomb',['count(*) as rn'])->exeSelect()[0]['rn'];
        
        $this->pm->select('Szamlatomb');
        if(!empty($this->selectedSortColumn['column']) && !empty($this->selectedSortColumn['dest'])){
            $this->pm->orderBy($this->selectedSortColumn['column'],$this->selectedSortColumn['dest']);
        }
        if($this->selectedPageNumber == 1 || $this->selectedStep == 1){
            $this->pm->limit($this->selectedPageNumber-1,$this->selectedStep);
        } else {
            $this->pm->limit($this->selectedPageNumber,$this->selectedStep);
        }
        
        return $this->pm->exeSelect();
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'szamlatomb-datatable';

        $this->dataColumns = array(
            "megnevezes" => array(
                'sortable' => false
            ),
            "szamla_elotag" => array(
                'sortable' => true
            ),
            "szamla_aktual_szam" => array(
                'sortable' => true
            ),
            "lezaras_datum" => array(
                'sortable' => false
            )
        );
        $this->operationColumns = array(
            array(
                'name' => 'edit',
            ),
            array(
                'name' => 'delete',
            )
        );
        $this->steps = array(1, 2, 5);
    }
}