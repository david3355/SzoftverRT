<?php

/**
 * Class PersistenceManager
 */
class PersistenceManager
{
    /**
     * @var DatabaseConnection
     */
    private $db;

    /**
     * @var string
     */
    private $mainObjectTableName;

    /**
     * @var PersistenceManager
     */
    static private $instance;

    /**
     * @var Config
     */
    private $config;

    /**
     * @return PersistenceManager
     */
    static function getInstance()
    {
        if (!isset(self::$instance)) self::$instance = new self(Database::getInstance());
        return self::$instance;
    }

    /**
     * @param DatabaseConnection $connection
     */
    final function __construct(Database $connection)
    {
        $this->db = $connection;
        $this->config = new Config();
        $this->mainObjectTableName = "objects";
    }

    /**
     * Az id alapján kikeresi a fő objektumtáblából a hozzá tartozó osztályt, és vakpéldányt készít belőle
     *
     * return object
     */
    final function getObject($id)
    {
        $sql = sprintf("SELECT * FROM %s WHERE id = %s", $this->mainObjectTableName, $id);

        $result = $this->db->query($sql);

        // Visszatérés vak példánnyal
        return new $result[0]['class']($result[0]['id']);
    }


    /**
     * Példányosítja az adott osztályt (vak példány), azonban a példányt adja vissza,
     * hanem a megadott paraméterek alapján végrehajtja a validációt, és a hibakódokkal tér vissza
     *
     * return hiba kódok array
     */
    final function validateCreateObject($class, array $params = null)
    {
        //vak példány létrehozása
        $object = new $class();
        return $object->validate($params);
    }

    /**
     * Létrehozza a vakpéldányt, validálja a megadott paraméterek alapján,
     * majd létrehozza a tényleges objektumot az adatbázisban, és visszatér az objektummal
     *
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
        return $this->config->get($classname);
    }

    /**
     * @return string
     */
    public final function getMainObjectTableName()
    {
        return $this->mainObjectTableName;
    }

}
