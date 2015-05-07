<?php

/**
 * Class Persistent
 */
abstract class Persistent
{
    private $table_name;

    /**
     * @var null
     */
    private $id;

    /**
     * @var PersistenceManager
     */
    protected $pm;

    /**
     * @var DatabaseConnection
     */
    protected $db;

    /**
     * @var
     */
    protected $mainObjectTable;

    /**
     * @param null $id
     */
    final function __construct($id = null)
    {
        $this->id = $id;
        $this->pm = PersistenceManager::getInstance();
        $this->db = Database::getInstance();
        $this->mainObjectTable = $this->pm->getMainObjectTableName();
        $this->table_name = $this->pm->getTableNameForClass(get_class($this));
    }

    /**
     * @return null
     */
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

        //3. objektum bejegyzése az osztályaihoz tartozó táblákba
        // Az array objektumokat kivesszük, és minden alosztály az OnAfterCreate-ben dolgozza fel
        // Az ott feldolgozott gyerekobjektumokat össze kell kapcsolni a hozzá tartozó ősobjektummal
        // Minden paramétert át kell adni az onAfterCreate-nek, nem csak a tömbértékűekre lehet szükség



        if (!is_null($params)) {

            // Ha a gyerekosztályok nem írják felül az onBeforeCreate-et, akkor az ősé fog lefutni, ha pedig implementálja a gyerekosztály, meg kell hívni az ős onBeforeCreate-jét
            $params = $this->onBeforeCreate($params);                    // Az még kérdéses, hogy ezt hol hívjuk és hogyan.


            $params['id'] = $this->id;
            do {
                $table = $this->pm->getTableNameForClass($class);
                $paramsForActual = self::getOwnParameters($class, $params);      // Mindegyik paraméterlista ugyanazt az id-t tartalmazza, de az adott osztályhoz tartozó paraméterekkel                

                foreach ($paramsForActual as $key => $value) {
                    if (is_array($value)) {
                        unset($paramsForActual[$key]);
                    }
                }
                $attribs = array_keys($paramsForActual);
                $values = array_values($paramsForActual);

                $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $table, implode(",", $attribs), implode("','", $values));
                $data = $this->db->query($sql);


            } while (($class = get_parent_class($class)) != "Persistent");

            $this->onAfterCreate($paramsForActual);                    // Az még kérdéses, hogy ezt hol hívjuk és hogyan

        }
        //4. alosztályok létrehozási tevékenységének futtatása
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

        // Csak azokat a táblákat joinoljuk, amilyen attribútumokra szükség van!
        if (is_null($field_names)) {
            $select = "*";
            $tables = array();
            $class = get_class($this);
            do {
                $table = $this->pm->getTableNameForClass($class);
                if (!in_array($table, $tables)) $tables[] = $table;
            } while (($class = get_parent_class($class)) != "Persistent");
        } else {
            $tables = $this->getTableNamesForAttributes($field_names);
            // Ha id-t akarunk lekérdezni, akkor azt minősíteni kell az egyik táblával, a többi attribútumnak különböznie kell a táblákban
            $idx = array_search('id', $field_names);
            if ($idx !== false) $field_names[$idx] = $tables[0] . '.' . $field_names[$idx];
            $select = implode(',', $field_names);
        }

        $from = $last = $tables[0];
        unset($tables[0]);
        foreach ($tables as $table) {
            $from .= ' INNER JOIN ' . $table . ' ON ' . $last . '.id=' . $table . '.id';
            $last = $table;
        }

        // Lekérdezzük a megfelelő mezőkhöz tartozó értékeket
        $sql = sprintf("SELECT %s FROM %s WHERE %s.id=%s", $select, $from, $last, $this->id);

        $result = $this->db->query($sql);

        return $result;
    }


    /**
     * Attribútumok beállítása
     *
     * $field_values=array(mezőnév=>érték, mezőnév=>érték, ...)
     */
    final protected function setFields(array $field_values)
    {
        $result = true;
        foreach ($field_values as $key => $value) {
            $table = $this->getTableNameForAttribute($key);
            $sql = sprintf("UPDATE %s SET %s='%s' WHERE id = %s", $table, $key, $value, $this->id);
            $result = $result && $this->db->query($sql);
        }
        return $result;
    }


    final function delete()
    {
        //objektum törlése a megfelelő táblákból

        // Ha vannak kompozícióval hozzá kapcsolódó objektumok, itt töröljük
        $result = $this->onBeforeDelete();

        // Lekérdezzük az objektumhoz tartozó összes táblát
        $tables = array();
        $class = get_class($this);
        do {
            $tables[] = $this->pm->getTableNameForClass($class);
        } while (($class = get_parent_class($class)) != "Persistent");

        // Töröljük az objektumot az összes táblából
        print_r($tables);

        foreach ($tables as $table) {
            $sql = sprintf("DELETE FROM %s WHERE id=%s", $table, $this->id);
            $result += $this->db->query($sql);
        }

        // Töröljük a fő objektumtáblából:

        $sql = sprintf("DELETE FROM %s WHERE id = %s", $this->mainObjectTable, $this->id);
        $result += $this->db->query($sql);

        return $result;
    }


    /**
     * @return mixed
     * @throws Exception
     */
    final protected function getNextUniqueId($azon_nev)
    {
        $result = $this->db->query("UPDATE {$this->table_name} SET {$azon_nev} = LAST_INSERT_ID({$azon_nev}+1)");

        return $this->db->getLastInsertID();
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
     * A(z opcionálisan) módosított paraméterekkel tér vissza
     */
    abstract protected function onBeforeCreate(array $params);

    /**
     *  Mielőtt az objektumot kitöröljük az adatbázisból, a kompozícióval hozzákapcsolt gyerekobjektumokat is törölni kell
     *  Csak utána törölhetjük az ősobjektumot
     *  return bool
     */
    abstract protected function onBeforeDelete();

    /**
     *  Minden osztály rendelkezik egy ilyen metódussal, mely a saját paramétereit adja vissza az összes paraméter közül
     * $params nélküli hívás: olyan array-el tér vissza, aminek a kulcsai az osztályhoz (illetve a hozzátartozó táblához) tartozó attribútumok
     * Hívás $params-t megadva: ugyanaz, csak a kulcsokhoz értékek is tartoznak, amit a $params-ból veszünk ki
     * $params: array('attributum1'=>'ertek1','attributum2'=>'ertek2'...)
     * @return mixed array
     */
    protected static function getOwnParameters($class, array $params = null)
    {
        $own = array_fill_keys($class::getOwnParameters(), '');        // A saját attribútumok lisája array kulcsként, értékek nélkül
        if ($params == null) return $own;
        foreach ($own as $key => $value) {
            $own[$key] = $params[$key];
        }
        return $own;        // A saját attribútumok lisája array kulcsként, minden attribútumhoz kivettük a hozzátartozó értéket a params-ból
    }

    /*
    array $select: null esetén mindent lekérdez, array megadása: ['id', 'nev'];
    array $where: null esetén nincs szűrés, array megadása: array(['attributum1', 'ertek1', 'true', 'AND'],['attributum2', 'ertek2', false])
                A $where array minden eleme egy másik array, mely egy feltételt reprezentál: ['attributum', 'ertek', bool regex, 'opcionális összekötő operátor']
    array $order: null esetén alapértelmezett rendezés, array megadása: ['id', 'nev']
    array $limit: null megadása esetén nincs limitálás, array megadása: [2,8]: az eredmények közül 2-től 8-ig adja vissza a rekordokat, érték megadása: 5: az első 5 rekordot adja vissza
    Example: select(['id', 'email', 'lakcim'], array(['email','vb12@gmail.com', true, 'AND'], ['nev', 'John', true]), ['id'], [3,6]);
    */
    final function select(array $select = null, array $where = null, array $order = null, $limit = null)
    {
        // Ha nincs megadva select, minden attribútumot lekérdezünk (az attribútumokra szükség van a táblák meghatározása miatt)
        if (is_null($select)) $select = $this->getAllAttributes();
        $tables = $this->getTableNamesForAttributes($select);

        // Ha id-t akarunk lekérdezni, akkor azt minősíteni kell az egyik táblával, a többi attribútumnak különböznie kell a táblákban
        $idx = array_search('id', $select);
        if ($idx !== false) $select[$idx] = $tables[0] . '.' . $select[$idx];

        $select_s = implode(',', $select);

        if ($order != null) {
            $idx = array_search('id', $order);
            if ($idx !== false) $order[$idx] = $tables[0] . '.' . $order[$idx];
            $order_s = 'ORDER BY ' . implode(',', $order);
        } else $order_s = "";

        // Feltételek meghatározása
        if (!is_null($where)) $where_s = 'WHERE ' . $this->concatenateConditions($where);
        else $where_s = "";

        // Csak azokat a táblákat joinoljuk, amilyen attribútumokra szükség van!
        $from_s = $last = $tables[0];
        unset($tables[0]);
        foreach ($tables as $table) {
            $from_s .= ' INNER JOIN ' . $table . ' ON ' . $last . '.id=' . $table . '.id';
            $last = $table;
        }

        if (is_null($limit)) $limit = "";
        else if (is_array($limit)) $limit = "LIMIT " . $limit[0] . ',' . $limit[1];
        else $limit = "LIMIT " . $limit;

        // Lekérdezzük a megfelelő mezőkhöz tartozó értékeket
        $sql = sprintf("SELECT %s FROM %s %s %s %s", $select_s, $from_s, $where_s, $order_s, $limit);

        $result = $this->db->query($sql);

        return $result;
    }

    ///////////// Segédfüggvények ///////////////

    /**
     *    $cond: feltételek tömbje
     *    Összefűzi a feltételeket, a megfelelő táblákkal együtt
     */
    private function concatenateConditions(array $cond)
    {
        $sql = "";
        foreach ($cond as $carray) {
            $table = $this->getTableNameForAttribute($carray[0]);
            if (isset($carray[3])) $operator = $carray[3];
            else $operator = "";
            if ($table !== false) {
                if ($carray[2] === false) $sql .= $table . '.' . $carray[0] . '=' . "'" . $carray[1] . "' " . $operator . ' ';
                else $sql .= $table . '.' . $carray[0] . ' LIKE ' . "'%" . $carray[1] . "%' " . $operator . ' ';
            }
        }
        return $sql;
    }

    /**
     *    Visszaadja azt a táblát, amenyben a paraméterként átadott attribútum szerepel
     *    Ha nem szerepel sehol, false-t ad vissza
     */
    private function getTableNameForAttribute($attribute)
    {
        $class = get_class($this);
        do {
            if (array_key_exists($attribute, self::getOwnParameters($class)))    // Lekérjük a paraméterként átadott osztályhoz tartozó mezőket, és ha a megadott attribútum köztük van
            {
                return $this->pm->getTableNameForClass($class);                // Akkor az osztályhoz lekérdezzük a táblát, és visszaadjuk
            }
        } while (($class = get_parent_class($class)) != "Persistent");                // Végigmegyünk az osztályhierarchián, míg el nem érünk a Persistent-hez
        return false;
    }

    private function getTableNamesForAttributes(array $attributes)
    {
        $tables = array();
        foreach ($attributes as $atb) {
            $table = $this->getTableNameForAttribute($atb);
            if ($table === false) continue;
            if (!in_array($table, $tables)) $tables[] = $table;
        }
        return $tables;
    }

    private function getAllAttributes()
    {
        $attributes = array();
        $class = get_class($this);
        do {
            $attributes = $attributes + $class::getOwnParameters();
        } while (($class = get_parent_class($class)) != "Persistent");
        return $attributes;
    }


}
