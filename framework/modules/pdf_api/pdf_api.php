<?php

include 'mpdf60/mpdf.php';

class PDF_API extends API_Module
{

    /**
     * return array of string
     * Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
     * A függvény nevét a ?module=függvényneve url paraméterben kell megadni.
     */
    function getSupportedFunctions()
    {
        return [
            'getPDF'
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
        switch($function){
            case 'getPDF':
                $mpdf = new mPDF();
                $mpdf->WriteHTML('<p>Hello World</p>');
                $mpdf->Output();
                break;
            default:
                break;
        }
    }
}