<html>
    
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Ügyfél teszt</title>
        <style>
            
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                padding: 5px;
            }
            
        </style>
    </head>
    
    <body>
<?php

require_once("../persistence_manager.php");
require_once("../ugyfel.php");

$pm = PersistenceManager::getInstance();

$ugyfel_adatok = array(
    'azonosito' => '12345678',
    'nev' => 'Elek',
    'cim_irszam' => '4031',
    'cim_varos' => 'Debrecen',
    'cim_utca_hsz' => 'Elek utca 10',
    'telefon' => '666-666',
    'email' => 'elek@elek.el'
);


$ugyfel = $pm->createObject('Ugyfel', $ugyfel_adatok);

echo 'Az új ügyfél: ';

echo implode(', ',$ugyfel->getUgyfelAdatok()).'<br/>';

$adatok = array('telefon' => '555-555');

$ugyfel->setUgyfelAdatok($adatok);

echo implode(', ',$ugyfel->getUgyfelAdatok()).'<br/>';

echo implode(', ', $pm->getObject($ugyfel->getID())->getUgyfelAdatok()).'<br/>';

echo 'Törlés sikeressége: ';
echo $ugyfel->deleteUgyfel();
?>
    </body>
    
</html>