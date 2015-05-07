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
     * @var
     */
    private $sql;

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
        $this->sql = '';
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
            return $errors;
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


    /**
     * @param $class
     * @param array $select
     */
    public function select($class, array $select = ['*'])
    {
        $this->sql['select'] = implode(',', $select);
        $this->sql['from'] = $this->getTableNameForClass($class);

        return $this;
    }

    /**
     * @param $attrib
     * @param $operator
     * @param $value
     */
    public function where($attrib, $operator, $value)
    {
        $this->sql['where'][] = $attrib . ' ' . $operator . " '" . $value . "' ";

        return $this;
    }

    /**
     * @param $attrib
     * @param $operator
     * @param $value
     * @return $this
     */
    public function orWhere($attrib, $operator, $value)
    {

        $this->sql['where'][] = ' OR ' . $attrib . ' ' . $operator . " '" . $value . "' ";

        return $this;
    }

    /**
     * @param $attrib
     * @param $operator
     * @param $value
     * @return $this
     */
    public function andWhere($attrib, $operator, $value)
    {

        $this->sql['where'][] = ' AND ' . $attrib . ' ' . $operator . " '" . $value . "' ";

        return $this;
    }

    /**
     * @param $limit
     * @param null $offset
     * @return $this
     */
    public function limit($limit, $offset = null)
    {
        if (is_null($offset)) {
            $this->sql['limit'] = $limit;
        } else {
            $this->sql['limit'] = $limit . ',' . $offset;
        }

        return $this;
    }

    /**
     * @param $attrib
     * @param string $order
     */
    public function orderBy($attrib, $order = 'DESC')
    {
        $this->sql['orderBy'] = $attrib . ' ' . $order;

        return $this;
    }

    /**
     * @param bool $iWantObject
     */
    public function get($iWantObject = false)
    {
        $sql = sprintf('SELECT %s FROM %s ', $this->sql['select'], $this->sql['from']);

        if (sizeof($this->sql['where'])) {
            $where = implode(' ', $this->sql['where']);

            $sql .= sprintf('WHERE %s ', $where);
        }

        if ($this->sql['orderBy']) {
            $sql .= sprintf('ORDERBY %s ', $this->sql['orderBy']);
        }

        if ($this->sql['limit']) {
            $sql .= sprintf('LIMIT %s', $this->sql['limit']);
        }

        $result = $this->db->query($sql);

        if (!$iWantObject) {
            return $result;
        }
    }

    /**
     * @param $class
     */
    public function delete($class)
    {
        // TODO csináld meg DÁVID!
    }

    /**
     * @param $class
     */
    public function update($class)
    {
        // TODO csináld meg DÁVID!
    }

}
