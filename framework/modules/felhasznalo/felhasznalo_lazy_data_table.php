<?php

class Felhasznalo_Lazy_Data_Table extends Abstract_Lazy_Data_Table
{

    private $pm;

    protected function getData(array $post = null)
    {

        return $this->pm->select('Felhasznalo')->get();
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'falhasznalo-datatable';

        $this->dataCoulombs = array(
            "id" => array(
                'name' => 'Azonosító',
                'sortable' => false
            ),
            "nev" => array(
                'name' => 'Név',
                'sortable' => false
            ),
            "email" => array(
                'name' => 'Email',
                'sortable' => false
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
        $this->operationCoulombs = array(
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
        $this->steps = array(50, 100, 500);
    }

}

