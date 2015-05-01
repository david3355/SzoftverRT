<?php

/**
 * Class FelhasznaloKomponens
 */
class FelhasznaloKomponens extends Site_Component {
    
    private $showFormPage = false;
    
    private $pm;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
    }
    
    function process()
    {
        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }
        
        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showFormPage = false;
        }
        
        if(!empty($_POST['save_and_new']) || !empty($_POST['save'])){
            $felhasznalo_adatok = array(
                'nev' => $_POST['nev'],
                'email' => $_POST['email'],
                'jelszo' => $_POST['jelszo'],
                'jog' => 1,
                'aktiv' => 1
            );
            
            $felhasznalo = $this->pm->createObject('Felhasznalo', $felhasznalo_adatok);
        }
    }

    function show()
    {   
         if ($this->showFormPage) {
            include_once 'view/form.page.php';
        } else {
            include_once 'view/list.page.php';
        }
    }
}