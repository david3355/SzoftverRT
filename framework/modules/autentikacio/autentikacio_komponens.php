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

        if (isset($_POST['login']) && !empty($_POST['login'])) {
            $this->auth->login($_POST['username'], $_POST['password']);
        }

        if (isset($_POST['logout']) && !empty($_POST['logout'])) {
            $this->auth->logout();
        }
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