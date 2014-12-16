<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnDataPeer::getOMClass()
include_once 'classes/model/BpmnData.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_DATA' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnDataPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_DATA';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnData';

    /** The total number of columns. */
    const NUM_COLUMNS = 12;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the DAT_UID field */
    const DAT_UID = 'BPMN_DATA.DAT_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_DATA.PRJ_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'BPMN_DATA.PRO_UID';

    /** the column name for the DAT_NAME field */
    const DAT_NAME = 'BPMN_DATA.DAT_NAME';

    /** the column name for the DAT_TYPE field */
    const DAT_TYPE = 'BPMN_DATA.DAT_TYPE';

    /** the column name for the DAT_IS_COLLECTION field */
    const DAT_IS_COLLECTION = 'BPMN_DATA.DAT_IS_COLLECTION';

    /** the column name for the DAT_ITEM_KIND field */
    const DAT_ITEM_KIND = 'BPMN_DATA.DAT_ITEM_KIND';

    /** the column name for the DAT_CAPACITY field */
    const DAT_CAPACITY = 'BPMN_DATA.DAT_CAPACITY';

    /** the column name for the DAT_IS_UNLIMITED field */
    const DAT_IS_UNLIMITED = 'BPMN_DATA.DAT_IS_UNLIMITED';

    /** the column name for the DAT_STATE field */
    const DAT_STATE = 'BPMN_DATA.DAT_STATE';

    /** the column name for the DAT_IS_GLOBAL field */
    const DAT_IS_GLOBAL = 'BPMN_DATA.DAT_IS_GLOBAL';

    /** the column name for the DAT_OBJECT_REF field */
    const DAT_OBJECT_REF = 'BPMN_DATA.DAT_OBJECT_REF';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('DatUid', 'PrjUid', 'ProUid', 'DatName', 'DatType', 'DatIsCollection', 'DatItemKind', 'DatCapacity', 'DatIsUnlimited', 'DatState', 'DatIsGlobal', 'DatObjectRef', ),
        BasePeer::TYPE_COLNAME => array (BpmnDataPeer::DAT_UID, BpmnDataPeer::PRJ_UID, BpmnDataPeer::PRO_UID, BpmnDataPeer::DAT_NAME, BpmnDataPeer::DAT_TYPE, BpmnDataPeer::DAT_IS_COLLECTION, BpmnDataPeer::DAT_ITEM_KIND, BpmnDataPeer::DAT_CAPACITY, BpmnDataPeer::DAT_IS_UNLIMITED, BpmnDataPeer::DAT_STATE, BpmnDataPeer::DAT_IS_GLOBAL, BpmnDataPeer::DAT_OBJECT_REF, ),
        BasePeer::TYPE_FIELDNAME => array ('DAT_UID', 'PRJ_UID', 'PRO_UID', 'DAT_NAME', 'DAT_TYPE', 'DAT_IS_COLLECTION', 'DAT_ITEM_KIND', 'DAT_CAPACITY', 'DAT_IS_UNLIMITED', 'DAT_STATE', 'DAT_IS_GLOBAL', 'DAT_OBJECT_REF', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('DatUid' => 0, 'PrjUid' => 1, 'ProUid' => 2, 'DatName' => 3, 'DatType' => 4, 'DatIsCollection' => 5, 'DatItemKind' => 6, 'DatCapacity' => 7, 'DatIsUnlimited' => 8, 'DatState' => 9, 'DatIsGlobal' => 10, 'DatObjectRef' => 11, ),
        BasePeer::TYPE_COLNAME => array (BpmnDataPeer::DAT_UID => 0, BpmnDataPeer::PRJ_UID => 1, BpmnDataPeer::PRO_UID => 2, BpmnDataPeer::DAT_NAME => 3, BpmnDataPeer::DAT_TYPE => 4, BpmnDataPeer::DAT_IS_COLLECTION => 5, BpmnDataPeer::DAT_ITEM_KIND => 6, BpmnDataPeer::DAT_CAPACITY => 7, BpmnDataPeer::DAT_IS_UNLIMITED => 8, BpmnDataPeer::DAT_STATE => 9, BpmnDataPeer::DAT_IS_GLOBAL => 10, BpmnDataPeer::DAT_OBJECT_REF => 11, ),
        BasePeer::TYPE_FIELDNAME => array ('DAT_UID' => 0, 'PRJ_UID' => 1, 'PRO_UID' => 2, 'DAT_NAME' => 3, 'DAT_TYPE' => 4, 'DAT_IS_COLLECTION' => 5, 'DAT_ITEM_KIND' => 6, 'DAT_CAPACITY' => 7, 'DAT_IS_UNLIMITED' => 8, 'DAT_STATE' => 9, 'DAT_IS_GLOBAL' => 10, 'DAT_OBJECT_REF' => 11, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnDataMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnDataMapBuilder');
    }
    /**
     * Gets a map (hash) of PHP names to DB column names.
     *
     * @return     array The PHP to DB name map for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
     */
    public static function getPhpNameMap()
    {
        if (self::$phpNameMap === null) {
            $map = BpmnDataPeer::getTableMap();
            $columns = $map->getColumns();
            $nameMap = array();
            foreach ($columns as $column) {
                $nameMap[$column->getPhpName()] = $column->getColumnName();
            }
            self::$phpNameMap = $nameMap;
        }
        return self::$phpNameMap;
    }
    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants TYPE_PHPNAME,
     *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return     string translated name of the field.
     */
    static public function translateFieldName($name, $fromType, $toType)
    {
        $toNames = self::getFieldNames($toType);
        $key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
        }
        return $toNames[$key];
    }

    /**
     * Returns an array of of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants TYPE_PHPNAME,
     *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return     array A list of field names
     */

    static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, self::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
        }
        return self::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *      $c->addAlias("alias1", TablePeer::TABLE_NAME);
     *      $c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. BpmnDataPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnDataPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      criteria object containing the columns to add.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria)
    {

        $criteria->addSelectColumn(BpmnDataPeer::DAT_UID);

        $criteria->addSelectColumn(BpmnDataPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnDataPeer::PRO_UID);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_NAME);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_TYPE);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_IS_COLLECTION);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_ITEM_KIND);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_CAPACITY);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_IS_UNLIMITED);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_STATE);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_IS_GLOBAL);

        $criteria->addSelectColumn(BpmnDataPeer::DAT_OBJECT_REF);

    }

    const COUNT = 'COUNT(BPMN_DATA.DAT_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_DATA.DAT_UID)';

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }
    /**
     * Method to select one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      Connection $con
     * @return     BpmnData
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnDataPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }
        return null;
    }
    /**
     * Method to do selects.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      Connection $con
     * @return     array Array of selected Objects
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, $con = null)
    {
        return BpmnDataPeer::populateObjects(BpmnDataPeer::doSelectRS($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect()
     * method to get a ResultSet.
     *
     * Use this method directly if you want to just get the resultset
     * (instead of an array of objects).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      Connection $con the connection to use
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     * @return     ResultSet The resultset object with numerically-indexed fields.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectRS(Criteria $criteria, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        if (!$criteria->getSelectColumns()) {
            $criteria = clone $criteria;
            BpmnDataPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        // BasePeer returns a Creole ResultSet, set to return
        // rows indexed numerically.
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function populateObjects(ResultSet $rs)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = BpmnDataPeer::getOMClass();
        $cls = Propel::import($cls);
        // populate the object(s)
        while ($rs->next()) {

            $obj = new $cls();
            $obj->hydrate($rs);
            $results[] = $obj;

        }
        return $results;
    }

    /**
     * Returns the number of rows matching criteria, joining the related BpmnProcess table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinBpmnProcess(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Returns the number of rows matching criteria, joining the related BpmnProject table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinBpmnProject(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnData objects pre-filled with their BpmnProcess objects.
     *
     * @return     array Array of BpmnData objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinBpmnProcess(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnDataPeer::addSelectColumns($c);
        $startcol = (BpmnDataPeer::NUM_COLUMNS - BpmnDataPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProcessPeer::addSelectColumns($c);

        $c->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnDataPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnProcessPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj2 = new $cls();
            $obj2->hydrate($rs, $startcol);

            $newObject = true;
            foreach($results as $temp_obj1) {
                $temp_obj2 = $temp_obj1->getBpmnProcess(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    // e.g. $author->addBookRelatedByBookId()
                    $temp_obj2->addBpmnData($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnDatas();
                $obj2->addBpmnData($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnData objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnData objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinBpmnProject(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnDataPeer::addSelectColumns($c);
        $startcol = (BpmnDataPeer::NUM_COLUMNS - BpmnDataPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnDataPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnProjectPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj2 = new $cls();
            $obj2->hydrate($rs, $startcol);

            $newObject = true;
            foreach($results as $temp_obj1) {
                $temp_obj2 = $temp_obj1->getBpmnProject(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    // e.g. $author->addBookRelatedByBookId()
                    $temp_obj2->addBpmnData($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnDatas();
                $obj2->addBpmnData($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
    {
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $criteria->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnData objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnData objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnDataPeer::addSelectColumns($c);
        $startcol2 = (BpmnDataPeer::NUM_COLUMNS - BpmnDataPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProcessPeer::NUM_COLUMNS;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $c->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnDataPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);


                // Add objects for joined BpmnProcess rows
    
            $omClass = BpmnProcessPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj2 = new $cls();
            $obj2->hydrate($rs, $startcol2);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj2 = $temp_obj1->getBpmnProcess(); // CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj2->addBpmnData($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnDatas();
                $obj2->addBpmnData($obj1);
            }


                // Add objects for joined BpmnProject rows
    
            $omClass = BpmnProjectPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj3 = new $cls();
            $obj3->hydrate($rs, $startcol3);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj3 = $temp_obj1->getBpmnProject(); // CHECKME
                if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj3->addBpmnData($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnDatas();
                $obj3->addBpmnData($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related BpmnProcess table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinAllExceptBpmnProcess(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Returns the number of rows matching criteria, joining the related BpmnProject table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinAllExceptBpmnProject(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnDataPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnDataPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnData objects pre-filled with all related objects except BpmnProcess.
     *
     * @return     array Array of BpmnData objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptBpmnProcess(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        // $c->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnDataPeer::addSelectColumns($c);
        $startcol2 = (BpmnDataPeer::NUM_COLUMNS - BpmnDataPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnDataPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnDataPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnProjectPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj2  = new $cls();
            $obj2->hydrate($rs, $startcol2);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj2 = $temp_obj1->getBpmnProject(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj2->addBpmnData($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnDatas();
                $obj2->addBpmnData($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnData objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnData objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptBpmnProject(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        // $c->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnDataPeer::addSelectColumns($c);
        $startcol2 = (BpmnDataPeer::NUM_COLUMNS - BpmnDataPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnDataPeer::PRO_UID, BpmnProcessPeer::PRO_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnDataPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnProcessPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj2  = new $cls();
            $obj2->hydrate($rs, $startcol2);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj2 = $temp_obj1->getBpmnProcess(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj2->addBpmnData($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnDatas();
                $obj2->addBpmnData($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return     TableMap
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
    }

    /**
     * The class that the Peer will make instances of.
     *
     * This uses a dot-path notation which is tranalted into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @return     string path.to.ClassName
     */
    public static function getOMClass()
    {
        return BpmnDataPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnData or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnData object containing data that is used to create the INSERT statement.
     * @param      Connection $con the connection to use
     * @return     mixed The new primary key.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from BpmnData object
        }


        // Set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->begin();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }

        return $pk;
    }

    /**
     * Method perform an UPDATE on the database, given a BpmnData or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnData object containing data create the UPDATE statement.
     * @param      Connection $con The connection to use (specify Connection exert more control over transactions).
     * @return     int The number of affected rows (if supported by underlying database driver).
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $selectCriteria = new Criteria(self::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(BpmnDataPeer::DAT_UID);
            $selectCriteria->add(BpmnDataPeer::DAT_UID, $criteria->remove(BpmnDataPeer::DAT_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_DATA table.
     *
     * @return     int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll($con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->begin();
            $affectedRows += BasePeer::doDeleteAll(BpmnDataPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnData or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnData object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      Connection $con the connection to use
     * @return     int  The number of affected rows (if supported by underlying database driver).
     *             This includes CASCADE-related rows
     *              if supported by native driver or if emulated using Propel.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
    */
    public static function doDelete($values, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BpmnDataPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnData) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnDataPeer::DAT_UID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->begin();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given BpmnData object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnData $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnData $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnDataPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnDataPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->containsColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(BpmnDataPeer::DATABASE_NAME, BpmnDataPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnData
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnDataPeer::DATABASE_NAME);

        $criteria->add(BpmnDataPeer::DAT_UID, $pk);


        $v = BpmnDataPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      Connection $con the connection to use
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria();
            $criteria->add(BpmnDataPeer::DAT_UID, $pks, Criteria::IN);
            $objs = BpmnDataPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnDataPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnDataMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnDataMapBuilder');
}

