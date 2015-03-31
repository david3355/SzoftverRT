<?

$pm=PersistenceManager::getInstance();

$szamla_adatok=array(
  'kiallitas_datuma'=>'2015-03-28'
  ,'tetelek'=>array(
    array('megnevezes'=>'Oktatás','osszeg'=>'5000')
    ,array('megnevezes'=>'Tárhely','osszeg'=>'15000')
  )
);


$szamla=$pm->createObject('Szamla',$szamla_adatok);


echo 'Az új számla sorszáma: ';

echo $szamla->getSorszam();

