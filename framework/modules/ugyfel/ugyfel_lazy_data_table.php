<?php

class Ugyfel_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    private $pm;

    protected function getData(array $post = null)
    {
        $find = '%'.$post['search_field']. '%';
        if($post['search_button']) $data = $data = $this->pm->select('Ugyfel')->where('azonosito', 'LIKE', $find)->orWhere('nev', 'LIKE', $find)->exeSelect();
        else $data = $this->pm->select('Ugyfel')->exeSelect();
        $ugyfelek = array();
        foreach($data as $ugyfel)
        {
            $uj = array();
            foreach($ugyfel as $k=>$v) $uj[$k]=$v;
            $uj['cim'] = $ugyfel['cim_irszam'] . ' ' . $ugyfel['cim_varos'] . ', ' . $ugyfel['cim_utca_hsz'];
            $ugyfelek[] = $uj;
        }
        return $ugyfelek;
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
            "azonosito" => array(
                'name' => 'Ügyfél azonosító',
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