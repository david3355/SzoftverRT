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


$felh_adatok_oroklodes = array
(
    'nev' => 'John Boss',
    'email' => 'theboss@gmail.com',
    'jelszo' => '4Lm4F4',
    'jog' => '1',
    'aktiv' => '1',
    'lakcim'=>'3300, Eger',
    'telszam'=>'06302345678',
    'vez_szam'=>'000255MG'
);

$pm ->createObject('Vezeto', $felh_adatok);



class Dolgozo extends Felhasznalo
{

    protected function onAfterCreate(array $params = null)
    {
        parent::onAfterCreate($params); // TODO: Change the autogenerated stub
    }

    protected function onBeforeCreate(array &$params = null)
    {
        parent::onBeforeCreate($params); // TODO: Change the autogenerated stub
    }

    protected function onBeforeDelete()
    {
        parent::onBeforeDelete(); // TODO: Change the autogenerated stub
    }

    public function validate(array $params = null)
    {
        return parent::validate($params); // TODO: Change the autogenerated stub
    }
}


class Vezeto extends Dolgozo
{

    protected function onAfterCreate(array $params = null)
    {
        parent::onAfterCreate($params); // TODO: Change the autogenerated stub
    }

    protected function onBeforeCreate(array &$params = null)
    {
        parent::onBeforeCreate($params); // TODO: Change the autogenerated stub
    }

    protected function onBeforeDelete()
    {
        parent::onBeforeDelete(); // TODO: Change the autogenerated stub
    }

    public function validate(array $params = null)
    {
        return parent::validate($params); // TODO: Change the autogenerated stub
    }
}
