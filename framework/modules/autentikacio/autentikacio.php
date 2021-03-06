<?php

class Autentikacio extends Site_Authenticator
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

    public function login($username, $password)
    {
        $msg = array();
        $pm = PersistenceManager::getInstance();

        $user = $pm->select('Felhasznalo', ['id', 'nev', 'jelszo', 'salt'])
            ->where('nev', '=', $username)->exeSelect();

        if (empty($user)) {
            $msg[] = 'NINCS_ILYEN_FELHASZNALO';
        }

        $hash = hash('sha256', $password . $user[0]['salt']);

        if ($hash === $user[0]['jelszo']) {
            $_SESSION[self::USER_SESSION_KEY] = $user[0]['id'];
            $msg[] = 'SIKERES_BEJELENTKEZES';
        } else {
            $msg[] = 'HIBAS_FELHASZNALONEV_VAGY_JELSZO';
        }

        return $msg;
    }

    public function logout()
    {
        unset($_SESSION[self::USER_SESSION_KEY]);
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
        return isset($_SESSION[self::USER_SESSION_KEY]) && !empty($_SESSION[self::USER_SESSION_KEY]);
    }

    /*login from webApp
 Lekérdezi a kapott usernév - jelszó párosra egyező felhasználót.
 @params
         -usernév
         -jelszó
 @return true|false, exception*/
    /*public function login($username, $jelszo)
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
    }*/
}

