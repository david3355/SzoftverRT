<?php

class Penztar_Lazy_Data_Table  extends Abstract_Lazy_Data_Table{

    protected function getData(array $post = null)
    {
        $find = '%'.$post['search_field']. '%';

        if(!empty($post['search_button']) || !empty($post['search_field'])) $this->pm->where('megnevezes', 'LIKE', $find);
        $this->numberOfAllRows = $this->pm->select('Penztar',['count(*) as rn'])->exeSelect()[0]['rn'];

        if(!empty($post['search_button']) || !empty($post['search_field'])) $this->pm->where('megnevezes', 'LIKE', $find);

        return $this->pm->select('Penztar')->exeSelect();
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'penztar-datatable';

        $this->dataColumns = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "megnevezes" => array(
                'name' => 'Megnevezés',
                'sortable' => false
            ),
            "egyenleg" => array(
                'name' => 'Egyenleg',
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