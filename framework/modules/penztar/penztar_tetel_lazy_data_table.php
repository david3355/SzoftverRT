<?php

class Penztar_Tetel_Lazy_Data_Table  extends Abstract_Lazy_Data_Table{

    protected function getData(array $post = null)
    {   
        $penztarId = $post['id'];
        
        $find = '%'.$post['search_tetel_field']. '%';

        if(!empty($post['search_button']) || !empty($post['search_tetel_field'])) $this->pm->where('penztar_fk', '=', $penztarId)->andWhere('megnevezes', 'LIKE', $find);
        $this->numberOfAllRows = $this->pm->select('PenztarTetel',['count(*) as rn'])->exeSelect()[0]['rn'];

        $this->pm->select('PenztarTetel')->where('penztar_fk', '=', $penztarId);
        if(!empty($post['search_button']) || !empty($post['search_tetel_field'])) $this->pm->andWhere('megnevezes', 'LIKE', $find);
        
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

        $this->tableName = 'penztar-tetel-datatable';

        $this->dataColumns = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "megnevezes" => array(
                'name' => 'Megnevezés',
                'sortable' => false
            ),
            "osszeg" => array(
                'name' => 'Összeg',
                'sortable' => false
            ),
            "datum" => array(
                'name' => 'Dátum',
                'sortable' => false
            ),
            "storno" => array(
                'name' => 'Sztornó',
                'sortable' => false
            )
        );

        $this->operationColumns = array(
            array(
                'name' => 'storno',
                'text' => 'Sztornózás'
            )
        );
    }
}