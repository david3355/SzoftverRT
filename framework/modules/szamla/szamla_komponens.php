<?php

/**
 * Class SzamlaKomponens
 */
class SzamlaKomponens extends Site_Component
{

    private $pm;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
    }

    function process()
    {
        // TODO: Implement process() method.
    }

    function show()
    {
        if ($_GET['subpage'] === 'uj-szamla') {
            include_once 'view/form.page.php';
        } else {
            include_once 'view/list.page.php';
        }
    }
}