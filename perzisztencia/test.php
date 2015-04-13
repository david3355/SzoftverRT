<?php

$pm = PersistenceManager::getInstance();

$felh_adatok = array
(
    'nev' => 'Teszt Elek',
    'email' => 'te@gmail.com',
    'jelszo' => '4Lm4F4',
    'jog' => '0',
    'aktiv' => '1'
);

$pm ->createObject('Felhasznalo', $felh_adatok);
