<?php


class Ugyfel_API extends API_Module
{

    private $api_key = array("2e6766863522c270667cd91952db15f5");

    /**
     * return array of string
     * Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
     * A függvény nevét a ?module=függvényneve url paraméterben kell megadni.
     */
    function getSupportedFunctions()
    {
        return [
            'allUgyfel',
            'getUgyfel',
            'postUgyfel',
            'putUgyfel',
            'deleteUgyfel'
        ];
    }

    /**
     * $function=$_GET['function']
     * $params=$_GET
     * $data=$_POST
     *
     * A http választ a kimenetre kell írni, és headert beállítani
     * A $function elfogadott értékeit a getSupportedFunctions metódusban kell visszaadni.
     */
    function handleRequest($function, array $params, array $data = null)
    {

        if(!$this->checkAuth($params['key'])){
            header('Content-Type: text/json', false, 403);

            echo json_encode(['msg' => 'Nincs jogosultsága használni ezt a funkciót!']);
            return;
        }

        $pm = PersistenceManager::getInstance();

        switch ($function) {
            case 'allUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode($pm->select('Felhasznalo')->get());
                break;
            case 'getUgyfel':
                $data = ['azon' => '0123QWERTY',
                    'nev' => 'Nev nev',
                    'cim' => '1111 Varos Cim 1.',
                    'telefon' => '21321313',
                    'email' => 'email@email.com'];

                header('Content-Type: text/json', false, 200);

                echo json_encode($data);

                break;
            case 'postUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode(['msg' => 'Sikeres ügyfél felvétel!']);

                break;
            case 'putUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode(['msg' => 'Sikeres ügyfél módosítás!']);

                break;
            case 'deleteUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode(['msg' => 'Sikeres ügyfél törlés!']);

                break;
            default:
                header('Content-Type: text/json', false, 404);

                echo json_encode(['msg' => 'A keresett funkció nem található!']);
                break;
        }

    }


    /**
     * @param $key
     * @return bool
     */
    private function checkAuth($key)
    {
        return in_array($key, $this->api_key);
    }
}