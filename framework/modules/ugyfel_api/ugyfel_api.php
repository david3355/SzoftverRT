<?php


class Ugyfel_API extends API_Module
{

    /**
     * return array of string
     * Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
     * A függvény nevét a ?module=függvényneve url paraméterben kell megadni.
     */
    function getSupportedFunctions()
    {
        return [
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

        $pm = PersistenceManager::getInstance();

        switch ($function) {
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

                echo json_encode(['msg'=>'Sikeres ügyfél felvétel!']);

                break;
            case 'putUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode(['msg'=>'Sikeres ügyfél módosítás!']);

                break;
            case 'deleteUgyfel':

                header('Content-Type: text/json', false, 200);

                echo json_encode(['msg'=>'Sikeres ügyfél törlés!']);

                break;
            default:
                header('Content-Type: text/json', false, 404);

                echo json_encode(['msg'=>'A keresett funkció nem található!']);
                break;
        }

    }
}