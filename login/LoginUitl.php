<?php

class LoginUtil {
    
    const USER_SESSION_KEY = "ACTUAL_USER";
    
    static private $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function __construct()
    {

    }
    
    public function login($user){
        $_SESSION[self::USER_SESSION_KEY] = $user;
    }
    
    public function logout(){
        session_destroy();
    }
    
    public function getActualUser(){
        return $_SESSION[self::USER_SESSION_KEY];
    }
    
}

