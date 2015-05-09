<?php

/**
 * Class Felhasznalo
 */
class Felhasznalo extends Persistent
{
    /**
     * @param array $params
     */
    protected function onAfterCreate(array $params = null)
    {

    }

    /**
     * @param array $params
     */
    protected function onBeforeCreate(array $params)
    {
        // sha-256 hash: pontosan 64 karakter
        // Salted hash:
        $params['salt'] = $this->generateSalt();
        $params['jelszo'] = hash('sha256', $params['jelszo'] . $params['salt']);
        return $params;
    }

    /**
     *
     */
    protected function onBeforeDelete()
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function validate(array $params = null)
    {
        $errors = array();

        if (empty($params['email'])) $errors[] = 'EMAIL_NINCS_MEGADVA';

        /*nev:
            -nem üres
            -egyedi
            -min. 3 hosszú*/
        if (isset($params['nev'])) {
            if (empty($params['nev'])) $errors[] = "USERNEV_NINCS_MEGADVA";
            if (strlen($params['nev']) < 3) $errors[] = "ROVID_USER_NEV";
            //nem egyedi, mert már van ilyen user_nev
            $users = PersistenceManager::getInstance()->select('Felhasznalo')->where('nev', '=', $params['nev'])->exeSelect();
            if (!empty($users)) $errors[] = "HASZNALT_USER_NEV";
        }

        /*jelszo:
            -nem üres
            -min. 6 hosszú
            -kis-nagy betű, szám*/

        if (isset($params['jelszo'])) {
             if (empty($params['jelszo'])) $errors[] = "JELSZO_NINCS_MEGADVA";
            if (strlen($params['jelszo']) < 6) $errors[] = "ROVID_JELSZO";
            if (!$this->isPasswordSecure($params['jelszo'])) $errors[] = "KIS_BETU_NAGY_BETU_SZAM_SZUKSEGES";
        }

        return $errors;
    }

    /**
     * @param $pwd
     * @return bool
     */
    private function isPasswordSecure($pwd)
    {
        return preg_match('/[A-Z]+/', $pwd) && preg_match('/[0-9]+/', $pwd) && preg_match('/[a-z]+/', $pwd);
    }

    /**
     * @return string
     */
    private function generateSalt($number = 8)
    {
        $randomString="";
        $charUniverse="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$number; $i++){
            $randInt=rand(0,61);
            $randChar=$charUniverse[$randInt];
            $randomString.=$randChar;
        }
        return $randomString;
    }

    /**
     * @param array $adatok
     * @return array|bool
     */
    function setFelhasznaloAdatok(array $adatok)
    {
        $err = $this->validate($adatok);
        if (empty($err)) {
            return $this->setFields($adatok);
        }
        return $err;
    }

    function setActive($active)
    {
        return $this->setFields(array('aktiv'=>$active));
    }

    function getFelhasznaloAdatok()
    {
        return $this->getFields(array('id', 'nev', 'email', 'jog', 'aktiv'));
    }

    protected static function getOwnParameters() {
        return array('id', 'nev', 'email', 'jelszo', 'salt', 'jog', 'aktiv');
    }

}

