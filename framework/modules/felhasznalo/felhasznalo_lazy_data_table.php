<?php

class Felhasznalo_Lazy_Data_Table extends Abstract_Lazy_Data_Table
{

    private $pm;

    protected function getData(array $post = null)
    {
        $this->numberOfAllRows = $this->pm->select('Felhasznalo',['count(*) as rn'])->exeSelect()[0]['rn'];
        
        $this->pm->select('Felhasznalo');
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

        $this->tableName = 'falhasznalo-datatable';

        $this->dataColumns = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "nev" => array(
                'name' => 'Név',
                'sortable' => true
            ),
            "email" => array(
                'name' => 'Email',
                'sortable' => true
            ),
            "jog" => array(
                'name' => 'Jog',
                'sortable' => false
            ),
            "aktiv" => array(
                'name' => 'Aktív',
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
            ),
            array(
                'name' => 'inactive',
                'text' => 'Inaktiválás'
            )
        );
        $this->steps = array(1, 2, 5);
    }

}

