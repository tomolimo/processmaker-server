<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnGatewayPeer::getOMClass()
include_once 'classes/model/BpmnGateway.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_GATEWAY' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnGatewayPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_GATEWAY';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnGateway';

    /** The total number of columns. */
    const NUM_COLUMNS = 11;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the GAT_UID field */
    const GAT_UID = 'BPMN_GATEWAY.GAT_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_GATEWAY.PRJ_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'BPMN_GATEWAY.PRO_UID';

    /** the column name for the GAT_NAME field */
    const GAT_NAME = 'BPMN_GATEWAY.GAT_NAME';

    /** the column name for the GAT_TYPE field */
    const GAT_TYPE = 'BPMN_GATEWAY.GAT_TYPE';

    /** the column name for the GAT_DIRECTION field */
    const GAT_DIRECTION = 'BPMN_GATEWAY.GAT_DIRECTION';

    /** the column name for the GAT_INSTANTIATE field */
    const GAT_INSTANTIATE = 'BPMN_GATEWAY.GAT_INSTANTIATE';

    /** the column name for the GAT_EVENT_GATEWAY_TYPE field */
    const GAT_EVENT_GATEWAY_TYPE = 'BPMN_GATEWAY.GAT_EVENT_GATEWAY_TYPE';

    /** the column name for the GAT_ACTIVATION_COUNT field */
    const GAT_ACTIVATION_COUNT = 'BPMN_GATEWAY.GAT_ACTIVATION_COUNT';

    /** the column name for the GAT_WAITING_FOR_START field */
    const GAT_WAITING_FOR_START = 'BPMN_GATEWAY.GAT_WAITING_FOR_START';

    /** the column name for the GAT_DEFAULT_FLOW field */
    const GAT_DEFAULT_FLOW = 'BPMN_GATEWAY.GAT_DEFAULT_FLOW';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('GatUid', 'PrjUid', 'ProUid', 'GatName', 'GatType', 'GatDirection', 'GatInstantiate', 'GatEventGatewayType', 'GatActivationCount', 'GatWaitingForStart', 'GatDefaultFlow', ),
        BasePeer::TYPE_COLNAME => array (BpmnGatewayPeer::GAT_UID, BpmnGatewayPeer::PRJ_UID, BpmnGatewayPeer::PRO_UID, BpmnGatewayPeer::GAT_NAME, BpmnGatewayPeer::GAT_TYPE, BpmnGatewayPeer::GAT_DIRECTION, BpmnGatewayPeer::GAT_INSTANTIATE, BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE, BpmnGatewayPeer::GAT_ACTIVATION_COUNT, BpmnGatewayPeer::GAT_WAITING_FOR_START, BpmnGatewayPeer::GAT_DEFAULT_FLOW, ),
        BasePeer::TYPE_FIELDNAME => array ('GAT_UID', 'PRJ_UID', 'PRO_UID', 'GAT_NAME', 'GAT_TYPE', 'GAT_DIRECTION', 'GAT_INSTANTIATE', 'GAT_EVENT_GATEWAY_TYPE', 'GAT_ACTIVATION_COUNT', 'GAT_WAITING_FOR_START', 'GAT_DEFAULT_FLOW', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('GatUid' => 0, 'PrjUid' => 1, 'ProUid' => 2, 'GatName' => 3, 'GatType' => 4, 'GatDirection' => 5, 'GatInstantiate' => 6, 'GatEventGatewayType' => 7, 'GatActivationCount' => 8, 'GatWaitingForStart' => 9, 'GatDefaultFlow' => 10, ),
        BasePeer::TYPE_COLNAME => array (BpmnGatewayPeer::GAT_UID => 0, BpmnGatewayPeer::PRJ_UID => 1, BpmnGatewayPeer::PRO_UID => 2, BpmnGatewayPeer::GAT_NAME => 3, BpmnGatewayPeer::GAT_TYPE => 4, BpmnGatewayPeer::GAT_DIRECTION => 5, BpmnGatewayPeer::GAT_INSTANTIATE => 6, BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE => 7, BpmnGatewayPeer::GAT_ACTIVATION_COUNT => 8, BpmnGatewayPeer::GAT_WAITING_FOR_START => 9, BpmnGatewayPeer::GAT_DEFAULT_FLOW => 10, ),
        BasePeer::TYPE_FIELDNAME => array ('GAT_UID' => 0, 'PRJ_UID' => 1, 'PRO_UID' => 2, 'GAT_NAME' => 3, 'GAT_TYPE' => 4, 'GAT_DIRECTION' => 5, 'GAT_INSTANTIATE' => 6, 'GAT_EVENT_GATEWAY_TYPE' => 7, 'GAT_ACTIVATION_COUNT' => 8, 'GAT_WAITING_FOR_START' => 9, 'GAT_DEFAULT_FLOW' => 10, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnGatewayMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnGatewayMapBuilder');
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
            $map = BpmnGatewayPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. BpmnGatewayPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnGatewayPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_UID);

        $criteria->addSelectColumn(BpmnGatewayPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnGatewayPeer::PRO_UID);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_NAME);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_TYPE);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_DIRECTION);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_INSTANTIATE);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_ACTIVATION_COUNT);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_WAITING_FOR_START);

        $criteria->addSelectColumn(BpmnGatewayPeer::GAT_DEFAULT_FLOW);

    }

    const COUNT = 'COUNT(BPMN_GATEWAY.GAT_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_GATEWAY.GAT_UID)';

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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
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
     * @return     BpmnGateway
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnGatewayPeer::doSelect($critcopy, $con);
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
        return BpmnGatewayPeer::populateObjects(BpmnGatewayPeer::doSelectRS($criteria, $con));
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
            BpmnGatewayPeer::addSelectColumns($criteria);
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
        $cls = BpmnGatewayPeer::getOMClass();
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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnGateway objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnGateway objects.
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

        BpmnGatewayPeer::addSelectColumns($c);
        $startcol = (BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnGatewayPeer::getOMClass();

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
                    $temp_obj2->addBpmnGateway($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnGateways();
                $obj2->addBpmnGateway($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnGateway objects pre-filled with their BpmnProcess objects.
     *
     * @return     array Array of BpmnGateway objects.
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

        BpmnGatewayPeer::addSelectColumns($c);
        $startcol = (BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProcessPeer::addSelectColumns($c);

        $c->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnGatewayPeer::getOMClass();

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
                    $temp_obj2->addBpmnGateway($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnGateways();
                $obj2->addBpmnGateway($obj1); //CHECKME
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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $criteria->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnGateway objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnGateway objects.
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

        BpmnGatewayPeer::addSelectColumns($c);
        $startcol2 = (BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $c->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnGatewayPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);


                // Add objects for joined BpmnProject rows
    
            $omClass = BpmnProjectPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj2 = new $cls();
            $obj2->hydrate($rs, $startcol2);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj2 = $temp_obj1->getBpmnProject(); // CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj2->addBpmnGateway($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnGateways();
                $obj2->addBpmnGateway($obj1);
            }


                // Add objects for joined BpmnProcess rows
    
            $omClass = BpmnProcessPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj3 = new $cls();
            $obj3->hydrate($rs, $startcol3);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj3 = $temp_obj1->getBpmnProcess(); // CHECKME
                if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj3->addBpmnGateway($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnGateways();
                $obj3->addBpmnGateway($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
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
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnGatewayPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnGatewayPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnGateway objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnGateway objects.
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

        BpmnGatewayPeer::addSelectColumns($c);
        $startcol2 = (BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnGatewayPeer::PRO_UID, BpmnProcessPeer::PRO_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnGatewayPeer::getOMClass();

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
                    $temp_obj2->addBpmnGateway($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnGateways();
                $obj2->addBpmnGateway($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnGateway objects pre-filled with all related objects except BpmnProcess.
     *
     * @return     array Array of BpmnGateway objects.
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

        BpmnGatewayPeer::addSelectColumns($c);
        $startcol2 = (BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnGatewayPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnGatewayPeer::getOMClass();

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
                    $temp_obj2->addBpmnGateway($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnGateways();
                $obj2->addBpmnGateway($obj1);
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
        return BpmnGatewayPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnGateway or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnGateway object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from BpmnGateway object
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
     * Method perform an UPDATE on the database, given a BpmnGateway or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnGateway object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(BpmnGatewayPeer::GAT_UID);
            $selectCriteria->add(BpmnGatewayPeer::GAT_UID, $criteria->remove(BpmnGatewayPeer::GAT_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_GATEWAY table.
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
            $affectedRows += BasePeer::doDeleteAll(BpmnGatewayPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnGateway or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnGateway object or primary key or array of primary keys
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
            $con = Propel::getConnection(BpmnGatewayPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnGateway) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnGatewayPeer::GAT_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given BpmnGateway object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnGateway $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnGateway $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnGatewayPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnGatewayPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BpmnGatewayPeer::DATABASE_NAME, BpmnGatewayPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnGateway
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnGatewayPeer::DATABASE_NAME);

        $criteria->add(BpmnGatewayPeer::GAT_UID, $pk);


        $v = BpmnGatewayPeer::doSelect($criteria, $con);

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
            $criteria->add(BpmnGatewayPeer::GAT_UID, $pks, Criteria::IN);
            $objs = BpmnGatewayPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnGatewayPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnGatewayMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnGatewayMapBuilder');
}

