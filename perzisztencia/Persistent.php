<?php

require_once("PersistenceManager.php");

abstract class Persistent
{
    private $id;
    protected $pm;
    protected $db;
    protected $mainObjectTable;

    final function __construct($id = null)
    {
        $this->id = $id;
        $this->pm = PersistenceManager::getInstance();
        $this->db = DatabaseConnection::getInstance();
        $this->mainObjectTable = $this->pm->getMainObjectTableName();
    }

    final function getID()
    {
        return $this->id;
    }

    /**
     * Perzisztens objektum létrehozása
     */
    final function create(array $params = null)
    {
        //Csak vak pélányon futhat, amelynek még nincs id beállíva
        if (isset($this->id)) return;

        //1. objektum bejegyzése a fő objektum táblába
        $class = get_class($this);
        $sql = sprintf("INSERT INTO %s (class) VALUES ('%s')", $this->mainObjectTable, $class);
        $this->db->query($sql);

        //2. auto generált id lekérdezése, és beállítása $this->id -be
        $this->id = $this->db->getLastInsertID();

        // Az adatok felvétele előtt van lehetőség módosítani a paramétereken (a paramétereket referencia szerint adjuk át):
        $this->onBeforeCreate($params);

        //3. objektum bejegyzése az osztályaihoz tartozó táblákba
        // Az array objektumokat kivesszük, és minden alosztály az OnAfterCreate-ben dolgozza fel
        // Az ott feldolgozott gyerekobjektumokat össze kell kapcsolni a hozzá tartozó ősobjektummal
        // Minden paramétert át kell adni az onAfterCreate-nek, nem csak a tömbértékűekre lehet szükség

        if (!is_null($params)) {
            $params['id'] = $this->id;
            $table = $this->getTableName();

            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    unset($params[$key]);
                }
            }
            $attribs = array_keys($params);
            $values = array_values($params);

            $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $table, implode(",", $attribs), implode("','", $values));
            $data = $this->db->query($sql);
        }
        //4. alosztályok létrehozási tevékenységének futtatása
        $this->onAfterCreate($params);
    }


    /**
     * Attribútumok lekérdezése
     *
     * $field_names=array(mezőnév,mezőnév, ...)
     *
     * return array(mezőnév=>érték, mezőnév=>érték, ...)
     * Ha $field_names üres, akkor adjon vissza minden mezőt.
     */
    final protected function getFields(array $field_names = null)
    {
        //megadott mezők lekérdezése a megfelelő táblákból

        // Lekérdezzük az osztályhoz tartozó táblát
        $table = $this->getTableName();

        // Lekérdezzük a megfelelő mezőkhöz tartozó értékeket
        if (isset($field_names))
            $sql = sprintf("SELECT %s FROM %s WHERE id = %s", implode(',', $field_names), $table, $this->id);
        else {
            $sql = sprintf("SELECT * FROM %s WHERE id  = %s", $table, $this->id);
        }

        $result = $this->db->query($sql);

        // Visszaadjuk az adatokat

        return $result[0];

    }

    /**
     * Attribútumok beállítása
     *
     * $field_values=array(mezőnév=>érték, mezőnév=>érték, ...)
     */
    final protected function setFields(array $field_values)
    {

        //megadott mezők beállítása a megfelelő táblákba

        // Lekérdezzük az osztályhoz tartozó táblát
        $table = $this->getTableName();

        foreach($field_values as $key => $value){
            $updates[] = $key."='".$value."'";
        }

        $sql = sprintf("UPDATE %s SET %s WHERE id = %s", $table,implode(", ",$updates) , $this->id);
        
        $result = $this->db->query($sql);

        return $result;

    }

    final function delete()
    {
        //objektum törlése a megfelelő táblákból

        // Ha vannak kompozícióval hozzá kapcsolódó objektumok, itt töröljük
        $result3 = $this->onBeforeDelete();

        // Lekérdezzük az osztályhoz tartozó táblát
        $table = $this->getTableName();

        // Töröljük az objektumot a táblából

        $sql = sprintf("DELETE FROM %s WHERE id  = %s", $table, $this->id);

        $result1 = $this->db->query($sql);

        // Töröljük a fő objektumtáblából:

        $sql = sprintf("DELETE FROM %s WHERE id = %s", $this->mainObjectTable, $this->id);
        $result2 = $this->db->query($sql);



        return $result1 && $result2 && $result3;

    }

    /**
     * return hiba kódok array
     *
     * Létrehozási/módosítási paraméterek ellenőrzése
     * Alosztály implementálja
     */
    abstract function validate(array $params = null);

    /**
     * return void
     *
     * Tetszőleges létrehozási tevékenység.
     * Alosztály implementálja
     */
    abstract protected function onAfterCreate(array $params = null);

    /**
     * Az objektum létrehozása előtt lehetőség van a paraméterek módosítására, ellenőrzésére
     */
    abstract protected function onBeforeCreate(array &$params = null);

    /**
     *  Mielőtt az objektumot kitöröljük az adatbázisból, a kompozícióval hozzákapcsolt gyerekobjektumokat is törölni kell
     *  Csak utána törölhetjük az ősobjektumot
     *  return bool
     */
    abstract protected function onBeforeDelete();

    abstract protected static function getTableName();

}
