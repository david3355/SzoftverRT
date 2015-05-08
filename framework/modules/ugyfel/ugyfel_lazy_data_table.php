<?php

class Ugyfel_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    private $pm;

    protected function getData(array $post = null)
    {
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'ugyfel-datatable';

        $this->dataColumns = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "nev" => array(
                'name' => 'Név',
                'sortable' => false
            ),
            "cim" => array(
                'name' => 'Cím',
                'sortable' => false
            ),
            "telefon" => array(
                'name' => 'Telefon',
                'sortable' => false
            ),
            "email" => array(
                'name' => 'Email',
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