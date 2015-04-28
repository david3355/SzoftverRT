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
        $_SESSION[self::USER_SESSION_KEY]=NULL;
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
    
       /*login from webApp
    Lekérdezi a kapott usernév - jelszó párosra egyező felhasználót.
    @params
            -usernév
            -jelszó
    @return true|false, exception*/
    public function login($username, $jelszo)
    {
        $user = $this->getFields(null,['user_nev' => $username]);
        
        if(empty($user)){
            return false;
        }
        
        $pass=hash('sha256', $jelszo.$user['salt']);
        
        if($pass == $user['jelszo']){
            Autentikacio::getInstance()->login($user['id']);
            return true;
        } else {
            return false;
        }
    }
}

