<?

class Szamla extends Persistent{
  
  function validate(array $params=null){
    $errors=array();
    
    if (empty($params['kiallitas_datuma'])) $errors[]='KIALLITAS_DATUMA_URES';
    if (empty($params['szamlatomb'])) $errors[]='SZAMLATOMB_NINCS_MEGADVA';
    if (empty($params['tetelek'])) $errors[]='TETELEK_URES';
    
    $pm= PersistenceManager::getInstance();
    //Mivel egy számla tételek kompozíciója, ezért a leendő tételeket is a számla hozza létre, és validálja 
    foreach ($params['tetelek'] as $tetel_adatok) {
      $tetel_hibak= $pm->validateCreateObject('Szamla_Tetel',$tetel_adatok);
      //Ha voltak tétel hibák, akkor azokat a saját hibákhoz hozzáfűzzük
      $errors=array_merge($errors,$tetel_hibak);
    }
    
    return $errors;
  }

  protected function onAfterCreate(array $params=null){
  
    $sorszam=$params['szamlatomb']->getUjSorszam();
    
    //Sorszám beállítása
    $this->setFields(array('sorszam'=>$sorszam));
    
    //Tételek létrehozása
    $pm= PersistenceManager::getInstance();
    foreach ($params['tetelek'] as $tetel_adatok) {
      $pm->createObject('Szamla_Tetel',array('szamla'=>$this,'megnevezes'=>$tetel_adatok['megnevezes'],'osszeg'=>$tetel_adatok['osszeg']));    	
    }
  }
  
  function getSorszam(){
    $adatok= $this->getFields(array('sorszam'));
    return $adatok['sorszam'];
  }
  
  //Számla adatai csak olvashatóak  
  function getSzamlaAdatok(){
    return $this->getFields();
  }      
  
}






