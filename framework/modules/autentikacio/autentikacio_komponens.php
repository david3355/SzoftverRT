<?php

class AutentikacioKomponens extends Site_Component
{

    private $auth;

    protected function afterConstruction()
    {
        $this->auth = Autentikacio::getInstance();
    }

    function process()
    {
        // TODO: Implement process() method.
    }

    function show()
    {
        if (!$this->auth->isUserAuthorized()) {
            include_once 'view/login.box.php';
        } else {
            include_once 'view/logout.box.php';
        }
    }
}