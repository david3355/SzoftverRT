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

                $id = $params['id'];
                $pm = PersistenceManager::getInstance();
                $szamla = $pm->select('Szamla')->where('id', '=', $id)->exeSelect()[0];
                if(!is_null($szamla)) {
/*
                    return array('id', 'szlatomb_obj_id', 'szla_sorszam', 'kiallito_neve', 'kiallito_cim',
                        'kiallito_adoszam', 'kiallito_bszla', 'befogado_nev', 'befogado_cim', 'befogado_adoszam', 'befogado_bszla',
                        'fizetesi_mod', 'kiallitas_datum', 'teljesites_datum', 'fizetes_datum', 'megjegyzes');

                    return array('id', 'szamla_fk', 'vamtarifaszam', 'megnevezes', 'mennyiseg_egyseg', 'mennyiseg', 'afa', 'netto_ar', 'brutto_ar');

*/
                    $mpdf->WriteHTML(sprintf('<p>Sorszám: %s</p>', $szamla['szla_sorszam']));
                    $mpdf->WriteHTML(sprintf('<p>Kiállító neve: %s</p>', $szamla['kiallito_neve']));
                    $mpdf->WriteHTML(sprintf('<p>Kiállító címe: %s</p>', $szamla['kiallito_cim']));
                    $mpdf->WriteHTML(sprintf('<p>Kiállító adószáma: %s</p>', $szamla['kiallito_adoszam']));
                    $mpdf->WriteHTML(sprintf('<p>Kiállító bankszámla: %s</p>', $szamla['kiallito_bszla']));
                    $mpdf->WriteHTML(sprintf('<p>Befogadó neve: %s</p>', $szamla['befogado_nev']));
                    $mpdf->WriteHTML(sprintf('<p>Befogadó címe: %s</p>', $szamla['befogado_cim']));
                    $mpdf->WriteHTML(sprintf('<p>Befogadó adószáma: %s</p>', $szamla['befogado_adoszam']));
                    $mpdf->WriteHTML(sprintf('<p>Befogadó bankszámla: %s</p>', $szamla['befogado_bszla']));
                    switch($szamla['fizetesi_mod'])
                    {
                        case '1': $fm = 'Csekk'; break;
                        case '2': $fm = 'Készpénzes'; break;
                        case '3': $fm = 'Utalásos'; break;
                        case '4': $fm = 'Utánvétes'; break;
                    }
                    $mpdf->WriteHTML(sprintf('<p>Fizetési mód: %s</p>', $fm));
                    $mpdf->WriteHTML(sprintf('<p>Kiállítás dátuma: %s</p>', $szamla['kiallitas_datum']));
                    $mpdf->WriteHTML(sprintf('<p>Teljesítés dátuma: %s</p>', $szamla['teljesites_datum']));
                    $mpdf->WriteHTML(sprintf('<p>Fizetési dátum: %s</p>', $szamla['fizetes_datum']));
                    $mpdf->WriteHTML(sprintf('<p>Megjegyzés: %s</p>', $szamla['megjegyzes']));

                    // Tételek:

                    $mpdf->WriteHTML('<p>Tételek</p>');

                    $tetelek = $pm->select('SzamlaTetel')->where('szamla_fk', '=', $id)->exeSelect();
                    foreach($tetelek as $tetel)
                    {
                        $mpdf->WriteHTML('<p>');
                        $mpdf->WriteHTML(sprintf('Vámtarifaszám: %s, Megnevezés: %s, Mennyiség egység: %s, Mennyiség: %s, ÁFA: %s, Nettó ár: %s, Bruttó ár: %s',
                            $tetel['vamtarifaszam'], $tetel['megnevezes'], $tetel['mennyiseg_egyseg'], $tetel['mennyiseg'], $tetel['afa'], $tetel['netto_ar'], $tetel['brutto_ar']));
                        $mpdf->WriteHTML('</p>');
                    }

                    $mpdf->Output();
                }
                break;
            default:
                break;
        }
    }
}