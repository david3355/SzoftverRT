<?php

class Szamla_Lazy_Data_Table extends Abstract_Lazy_Data_Table
{

    private $pm;

    protected function getData(array $post = null)
    {
        $find = '%' . $post['search_field'] . '%';

        if (!empty($post['search_button']) && !empty($post['search_field'])) $this->pm->where('sorszam', 'LIKE', $find);
        $this->numberOfAllRows = $this->pm->select('Szamla', ['count(*) as rn'])->exeSelect()[0]['rn'];

        if (!empty($post['search_button']) && !empty($post['search_field'])) $this->pm->where('sorszam', 'LIKE', $find);
        $this->pm->select('Szamla');
        if (!empty($this->selectedSortColumn['column']) && !empty($this->selectedSortColumn['dest'])) {
            $this->pm->orderBy($this->selectedSortColumn['column'], $this->selectedSortColumn['dest']);
        }
        if ($this->selectedPageNumber == 1 || $this->selectedStep == 1) {
            $this->pm->limit($this->selectedPageNumber - 1, $this->selectedStep);
        } else {
            $this->pm->limit($this->selectedPageNumber, $this->selectedStep);
        }

        return $this->pm->exeSelect();
    }

    protected function init()
    {
        $this->pm = PersistenceManager::getInstance();

        $this->tableName = 'szamla-datatable';

        $this->dataColumns = array(
            "sorszam" => array(
                'name' => 'Sorszám',
                'sortable' => true
            ),
            "kiallitas" => array(
                'name' => 'Kiallítás dátuma',
                'sortable' => true
            ),
            "teljesites" => array(
                'name' => 'Teljesítés dátuma',
                'sortable' => true
            ),
            "fizetes" => array(
                'name' => 'Fizetési határidő',
                'sortable' => true
            ),
            "kibocsato" => array(
                'name' => 'Kibocsátó',
                'sortable' => false
            ),
            "befogado" => array(
                'name' => 'Befogadó',
                'sortable' => false
            ),
            "fizetesi_mod" => array(
                'name' => 'Fizetési mód',
                'sortable' => false
            ),
            "netto" => array(
                'name' => 'Nettó összeg',
                'sortable' => true
            ),
            "brutto" => array(
                'name' => 'Bruttó összeg',
                'sortable' => true
            ),
            "afa" => array(
                'name' => 'ÁFA összeg',
                'sortable' => true
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
                'name' => 'storno',
                'text' => 'Sztornózás'
            )
        );
        $this->steps = array(50, 100, 500);
    }
}