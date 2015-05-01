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
    protected function onBeforeCreate(array &$params = null)
    {
        /*Beraktam sha-256 hash-be, hogy pontosan 64 karakter legyen a jelszó a DB-hez!*/

        // Salted hash:
        $params['salt'] = $this->generateSalt();
        //$params['jelszo'] = password_hash($params['jelszo'].$params['salt'], PASSWORD_BCRYPT);
        $params['jelszo'] = hash('sha256', $params['jelszo'] . $params['salt']);
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

        if (empty($params['nev'])) $errors[] = 'NEV_NINCS_MEGADVA';
        if (empty($params['email'])) $errors[] = 'EMAIL_NINCS_MEGADVA';

        /*user_nev:
            -nem üres
            -egyedi
            -min. 3 hosszú*/
        if (!empty($params['user_nev'])) {
            if (strlen($params['user_nev']) >= 3) {
                //nem egyedi, mert már van ilyen user_nev
                if ($this->getFields(['user_nev'], ['user_nev' => $params['user_nev']])[0]['user_nev'] == true) {
                    $errors[] = "USED_USER_NEV";
                }
            } else {
                $errors[] = "ROVID_USER_NEV";
            }
        } else {
            $errors[] = "USERNEV_NINCS_MEGADVA";
        }

        /*jelszo:
            -nem üres
            -min. 6 hosszú
            -kis-nagy betű, szám*/
        if (!empty($params['jelszo'])) {
            if (strlen($params['jelszo']) >= 6) {
                if (!isPasswordSecure($params['jelszo'])) {
                    $errors[] = "KIS_BETU_NAGY_BETU_SZAM_SZUKSEGES";
                }
            } else {
                $errors[] = "ROVID_JELSZO";
            }
        } else {
            $errors[] = "JELSZO_NINCS_MEGADVA";
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
    private function generateSalt()
    {
        return mcrypt_create_iv(8, MCRYPT_DEV_URANDOM);
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

    protected function getOwnParameters(array $params = null) {
        
    }

}

