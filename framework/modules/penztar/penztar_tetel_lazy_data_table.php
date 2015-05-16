<?php

class Penztar_Tetel_Lazy_Data_Table  extends Abstract_Lazy_Data_Table{

    protected function getData(array $post = null)
    {   
        $penztarId = $post['id'];

        return $this->pm->select('PenztarTetel')->where('penztar_fk', '=', $penztarId)->exeSelect();
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