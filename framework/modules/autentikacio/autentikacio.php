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

	/*login from webApp
	@params
		-usernév
		-jelszó
	@return true|false, exception*/
	public function webAppLogin($username, $jelszo)
	{
		$jelszo=
		if()
		{
			$_SESSION['session']=hash('sha256', $username.date("Y-m-d H:i:s").salt);
			
		}
		else
		{
			throw new Exception("INVALID_USER");
		}
	}
	
    public function login($user)
    {
        $_SESSION[self::USER_SESSION_KEY] = $user;
    }

    public function logout()
    {
        $_SESSION['session']=NULL;
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

