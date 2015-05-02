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
            $this->onBeforeCreate($params);					// Az még kérdéses, hogy ezt hol hívjuk és hogyan.

            $params['id'] = $this->id;
            do
            {
                $table = $this->pm->getTableNameForClass($class);
                $paramsForActual = $class::getOwnParameters($params);      // Mindegyik paraméterlista ugyanazt az id-t tartalmazza, de az adott osztályhoz tartozó paraméterekkel


                foreach ($paramsForActual as $key => $value) {
                    if (is_array($value)) {
                        unset($paramsForActual[$key]);
                    }
                }
                $attribs = array_keys($paramsForActual);
                $values = array_values($paramsForActual);

                $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $table, implode(",", $attribs), implode("','", $values));
                $data = $this->db->query($sql);


            }while(($class = get_parent_class($class))!="Persistent");

            $this->onAfterCreate($paramsForActual);					// Az még kérdéses, hogy ezt hol hívjuk és hogyan

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
    final protected function getFields(array $select = null, array $where = null, array $order = null, $limit = "")
    {
        //megadott mezők lekérdezése a megfelelő táblákból
        $class = get_class($this);

        // Ha id-t akarunk lekérdezni, akkor azt minősíteni kell az egyik táblával, a többi attribútumnak különböznie kell a táblákban
        $idx = array_search('id', $select);
        if($idx !== false) $select[$idx] = $class.'.'.$select[$idx];

        if($order != null)
        {
            $idx = array_search('id', $order);
            if($idx !== false) $order[$idx] = $class.'.'.$order[$idx];
            $order = 'ORDER BY '.implode(',', $order);
        }
        else $order = "";

        // Feltételek meghatározása
        if ($where == null) $where = sprintf('%s.id = %s', $this->pm->getTableNameForClass($class), $this->getID());
        else $where = 'WHERE '.$this->catConditions($where, 'AND');

        $from = $last = $table = $this->pm->getTableNameForClass($class);

        while(($class = get_parent_class($class))!="Persistent")
        {
            $table = $this->pm->getTableNameForClass($class);
            $from .= ' INNER JOIN '.$table. ' ON '.$last.'.id='.$table.'.id';
            $last = $table;
        }

        if(!empty($limit)) $limit = "LIMIT ".$limit;

        if (isset($select)) $select = implode(',', $select);
        else $select = "*";

        // Lekérdezzük a megfelelő mezőkhöz tartozó értékeket
        $sql = sprintf("SELECT %s FROM %s %s %s %s", $select, $from, $where, $order, $limit);


        $result = $this->db->query($sql);

        return $result;
    }

    // Ha erre tud valaki szebb megoldást, írja nyugodtan :D
    private function catConditions(array $cond, $operator)
    {
        $sql = "";
        $i = 0;

        foreach ($cond as $key => $val) {
            $class = get_class($this);
            do
            {
                if(array_key_exists($key, $class::getOwnParameters()))
                {
                    $table = $this->pm->getTableNameForClass($class);
                    break;
                }
            }
            while(($class = get_parent_class($class))!="Persistent");

            $sql .= $table.'.'.$key . '=' ."'" .$val."'";
            if ($i < count($cond) - 1) $sql .= ' ' . $operator . ' ';
            $i++;
        }
        return $sql;
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
            $class = get_class($this);
            do
            {
                if(array_key_exists($key, $class::getOwnParameters()))
                {
                    $table = $this->pm->getTableNameForClass($class);
                    break;
                }
            }
            while(($class = get_parent_class($class))!="Persistent");
            $sql = sprintf("UPDATE %s SET %s='%s' WHERE id = %s", $table, $key, $value, $this->id);
            echo $sql.'<br>';
            $result = $result && $this->db->query($sql);
        }
        return $result;
    }

	/*Lista lekérdezés
		-attr: tömbben megadva a lekérendő attributumok, ha üres, akkor *
		-where: natívan megadva a where feltétele
		-order_by: natívan megadva mi szerint hogyan legyen rendezve
		-limit: tömbben a kezdőérték és darab megadva*/
	final function select(array $attr, $where, $order_by, array $limit)
	{
		if(!empty($attr))
		{
			foreach($attr as $k)
			{
				$attr.=$k.", ";
			}
			$attr=rtrim($attr, ',');
		}
		else
		{
			$attr="*";
		}
		
		if(!empty($where))
		{
			$where="WHERE ".$where;
		}
		
		if(!empty($order_by))
		{
			$order_by="ORDER BY ".$order_by;
		}
		
		if(!empty($limit))
		{
			$limit="LIMIT {$limit[0]}, {$limit[1]}";
		}
		
		return $this->db->query("SELECT {$attr} FROM {$this->table_name} {$where} {$order_by} {$limit}");
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
     */
    abstract protected function onBeforeCreate(array &$params = null);

    /**
     *  Mielőtt az objektumot kitöröljük az adatbázisból, a kompozícióval hozzákapcsolt gyerekobjektumokat is törölni kell
     *  Csak utána törölhetjük az ősobjektumot
     *  return bool
     */
    abstract protected function onBeforeDelete();


    /**
     *  Minden osztály a saját paramétereit adja vissza az összes paraméter közül
     * @param array $params
     * @return mixed array
     */
    abstract protected function getOwnParameters(array $params = null);


}
