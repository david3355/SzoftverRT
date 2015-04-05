<?php

require_once("persistence_manager.php");

abstract class Persistent
{
    private $id;
    private $pm;
    private $db;
    private $mainObjectTable;

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
        //Csak vak pélányon futhat.
        if (isset($this->id)) return;

        //1. objektum bejegyzése a fő objektum táblába
        $class = get_class($this);
        $sql = sprintf("INSERT INTO %s (class) VALUES ('%s')", $this->mainObjectTable, $class);
        $this->db->query($sql);

        //2. auto generált id lekérdezése, és beállítása $this->id -be
        $sql = sprintf("SELECT max(id) as id FROM %s", $this->mainObjectTable);
        $data = $this->db->query($sql);
        $this->id = $data[0]['id'];

        //3. objektum bejegyzése az osztályaihoz tartozó táblákba
        // Az array objektumokat kivesszük, és minden alosztály az OnAfterCreate-ben dolgozza fel
        // Az ott feldolgozott objektumokat össze kell kapcsolni a hozzá tartozó objektummal: a fő objektum id-ját felvesszük minden hozzá kapcsolódó objektumhoz

        $arrayValues = array();

        if (!is_null($params)) {
            $params['id'] = $this->id;
            $table = $this->getTableName();

            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    $arrayValues[$key] = $value;
                    unset($params[$key]);
                }
            }
            $attribs = array_keys($params);
            $values = array_values($params);

            $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $table, implode(",", $attribs), implode("','", $values));
            $data = $this->db->query($sql);
        }
        //4. alosztályok létrehozási tevékenységének futtatása
        $this->onAfterCreate($arrayValues);
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

        $attribs = array_keys($field_values);
        $values = array_values($field_values);

        $sql = sprintf("UPDATE %s SET (%s) VALUES ('%s') WHERE id = %s", $table, implode(",", $attribs), implode("','", $values), $this->id);
        
        $result = $this->db->query($sql);

        return $result;

    }

    final function delete()
    {
        //objektum törlése a megfelelő táblákból

        // Lekérdezzük az osztályhoz tartozó táblát
        $table = $this->getTableName();

        // Töröljük az objektumot a táblából

        $sql = sprintf("DELETE FROM %s WHERE id  = %s", $table, $this->id);

        $result1 = $this->db->query($sql);

        // Töröljük a fő objektumtáblából:

        $sql = sprintf("DELETE FROM %s WHERE id = %s", $this->mainObjectTable, $this->id);
        $result2 = $this->db->query($sql);

        return $result1 && $result2;

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

    abstract protected static function getTableName();

}
