<?php

class Szamlatomb_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    protected function getData(array $post = null)
    {
        $find = '%'.$post['search_field']. '%';

        if(!empty($post['search_button']) && !empty($post['search_field'])) $this->pm->where('megnevezes', 'LIKE', $find)->orWhere('szamla_elotag', 'LIKE', $find);
        $this->numberOfAllRows = $this->pm->select('Szamlatomb',['count(*) as rn'])->exeSelect()[0]['rn'];
        
        if(!empty($post['search_button']) && !empty($post['search_field'])) $this->pm->where('megnevezes', 'LIKE', $find)->orWhere('szamla_elotag', 'LIKE', $find);
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
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "megnevezes" => array(
                'name' => 'Megnevezés',
                'sortable' => true
            ),
            "szamla_elotag" => array(
                'name' => 'Előtag',
                'sortable' => true
            ),
            "szamla_aktual_szam" => array(
                'name' => 'Aktuális sorszám',
                'sortable' => false
            ),
            "lezaras_datum" => array(
                'name' => 'Lezárás dátuma',
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
                'name' => 'close',
                'text' => 'Zárás'
            )
        );
    }
}