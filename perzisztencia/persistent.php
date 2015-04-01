<?

require_once("persistence_manager.php");

abstract class Persistent
{
    private $id;
    private $pm;

    final function __construct($id = null)
    {
        $this->id = $id;
        $pm = PersistenceManager::getInstance();
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
        $objecttable = "objects";
        $class = get_class($this);
        $table = $this->getTableName();
        $sql = sprintf("INSERT INTO %s (class) VALUES ('%s')", $objecttable, $class);

        //2. auto generált id lekérdezése, és beállítása $this->id -be
        $sql = sprintf("SELECT max(id) FROM %s", $objecttable);

        //3. objektum bejegyzése az osztályaihoz tartozó táblákba

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
    }

    /**
     * Attribútumok beállítása
     *
     * $field_values=array(mezőnév=>érték, mezőnév=>érték, ...)
     */
    final protected function setFields(array $field_values)
    {
        //megadott mezők beállítása a megfelelő táblákba
    }

    final function delete()
    {
        //objektum törlése a megfelelő táblákból
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
