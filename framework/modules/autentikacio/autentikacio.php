<?php

class Autentikacio
{

    const USER_SESSION_KEY = "ACTUAL_USER";

    static private $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function login($user)
    {
        $_SESSION[self::USER_SESSION_KEY] = $user;
    }

    public function logout()
    {
        session_destroy();
    }

    public function getActualUser()
    {
        return $_SESSION[self::USER_SESSION_KEY];
    }

    /**
     * return:
     * true: ha felhasználó be van lépve és jogosult az adott belépési pontra
     * false: ha felhasználó be nincs lépve vagy nem jogosult az adott belépési pontra
     */
    function isUserAuthorized()
    {
        // TODO: Implement isUserAuthorized() method.
        return true;
    }
}

