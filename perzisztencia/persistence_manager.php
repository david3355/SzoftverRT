<?php

require_once("database.php");

class PersistenceManager
{
    private $db;
    private $mainObjectTableName;

    static private $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) self::$instance = new self(DatabaseConnection::getInstance());
        return self::$instance;
    }

    final function __construct(DatabaseConnection $connection)
    {
        $this->db = $connection;
        $this->mainObjectTableName = "objects";
    }

    /**
     * return object
     */
    final function getObject($id)
    {
        $sql = sprintf("SELECT * FROM %s WHERE id = %s",$this->mainObjectTableName,$id);

        $result = $this->db->query($sql);

        return new $result[0]['class'];
    }


    /**
     * return hiba kódok array
     */
    final function validateCreateObject($class, array $params = null)
    {
        //vak példány létrehozása
        $object = new $class();
        return $object->validate($params);
    }

    /**
     * return object
     */
    final function createObject($class, array $params = null, array &$errors = null)
    {
        //vak példány létrehozása
        $object = new $class();

        //validálás
        $errors = $object->validate($params);

        //Ha nem volt hiba, akkor létrehozzuk az objektumot, és visszaadjuk
        if (!$errors) {
            $object->create($params);
            return $object;
        } else {
            return null;
        }

    }

    /**
     * - Vagy switch case az osztályhoz és a táblához, vagy a táblanév mindig ugyanaz mint az osztály, vagy minden osztály eltárolja a táblanevet
     * return table name string
     */
    final function getTableNameForClass($classname)
    {
        return $classname::getTableName();
    }

    public final function getMainObjectTableName()
    {
        return $this->mainObjectTableName;
    }

}
