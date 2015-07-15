<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnActivityPeer::getOMClass()
include_once 'classes/model/BpmnActivity.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_ACTIVITY' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnActivityPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_ACTIVITY';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnActivity';

    /** The total number of columns. */
    const NUM_COLUMNS = 30;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the ACT_UID field */
    const ACT_UID = 'BPMN_ACTIVITY.ACT_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_ACTIVITY.PRJ_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'BPMN_ACTIVITY.PRO_UID';

    /** the column name for the ACT_NAME field */
    const ACT_NAME = 'BPMN_ACTIVITY.ACT_NAME';

    /** the column name for the ACT_TYPE field */
    const ACT_TYPE = 'BPMN_ACTIVITY.ACT_TYPE';

    /** the column name for the ACT_IS_FOR_COMPENSATION field */
    const ACT_IS_FOR_COMPENSATION = 'BPMN_ACTIVITY.ACT_IS_FOR_COMPENSATION';

    /** the column name for the ACT_START_QUANTITY field */
    const ACT_START_QUANTITY = 'BPMN_ACTIVITY.ACT_START_QUANTITY';

    /** the column name for the ACT_COMPLETION_QUANTITY field */
    const ACT_COMPLETION_QUANTITY = 'BPMN_ACTIVITY.ACT_COMPLETION_QUANTITY';

    /** the column name for the ACT_TASK_TYPE field */
    const ACT_TASK_TYPE = 'BPMN_ACTIVITY.ACT_TASK_TYPE';

    /** the column name for the ACT_IMPLEMENTATION field */
    const ACT_IMPLEMENTATION = 'BPMN_ACTIVITY.ACT_IMPLEMENTATION';

    /** the column name for the ACT_INSTANTIATE field */
    const ACT_INSTANTIATE = 'BPMN_ACTIVITY.ACT_INSTANTIATE';

    /** the column name for the ACT_SCRIPT_TYPE field */
    const ACT_SCRIPT_TYPE = 'BPMN_ACTIVITY.ACT_SCRIPT_TYPE';

    /** the column name for the ACT_SCRIPT field */
    const ACT_SCRIPT = 'BPMN_ACTIVITY.ACT_SCRIPT';

    /** the column name for the ACT_LOOP_TYPE field */
    const ACT_LOOP_TYPE = 'BPMN_ACTIVITY.ACT_LOOP_TYPE';

    /** the column name for the ACT_TEST_BEFORE field */
    const ACT_TEST_BEFORE = 'BPMN_ACTIVITY.ACT_TEST_BEFORE';

    /** the column name for the ACT_LOOP_MAXIMUM field */
    const ACT_LOOP_MAXIMUM = 'BPMN_ACTIVITY.ACT_LOOP_MAXIMUM';

    /** the column name for the ACT_LOOP_CONDITION field */
    const ACT_LOOP_CONDITION = 'BPMN_ACTIVITY.ACT_LOOP_CONDITION';

    /** the column name for the ACT_LOOP_CARDINALITY field */
    const ACT_LOOP_CARDINALITY = 'BPMN_ACTIVITY.ACT_LOOP_CARDINALITY';

    /** the column name for the ACT_LOOP_BEHAVIOR field */
    const ACT_LOOP_BEHAVIOR = 'BPMN_ACTIVITY.ACT_LOOP_BEHAVIOR';

    /** the column name for the ACT_IS_ADHOC field */
    const ACT_IS_ADHOC = 'BPMN_ACTIVITY.ACT_IS_ADHOC';

    /** the column name for the ACT_IS_COLLAPSED field */
    const ACT_IS_COLLAPSED = 'BPMN_ACTIVITY.ACT_IS_COLLAPSED';

    /** the column name for the ACT_COMPLETION_CONDITION field */
    const ACT_COMPLETION_CONDITION = 'BPMN_ACTIVITY.ACT_COMPLETION_CONDITION';

    /** the column name for the ACT_ORDERING field */
    const ACT_ORDERING = 'BPMN_ACTIVITY.ACT_ORDERING';

    /** the column name for the ACT_CANCEL_REMAINING_INSTANCES field */
    const ACT_CANCEL_REMAINING_INSTANCES = 'BPMN_ACTIVITY.ACT_CANCEL_REMAINING_INSTANCES';

    /** the column name for the ACT_PROTOCOL field */
    const ACT_PROTOCOL = 'BPMN_ACTIVITY.ACT_PROTOCOL';

    /** the column name for the ACT_METHOD field */
    const ACT_METHOD = 'BPMN_ACTIVITY.ACT_METHOD';

    /** the column name for the ACT_IS_GLOBAL field */
    const ACT_IS_GLOBAL = 'BPMN_ACTIVITY.ACT_IS_GLOBAL';

    /** the column name for the ACT_REFERER field */
    const ACT_REFERER = 'BPMN_ACTIVITY.ACT_REFERER';

    /** the column name for the ACT_DEFAULT_FLOW field */
    const ACT_DEFAULT_FLOW = 'BPMN_ACTIVITY.ACT_DEFAULT_FLOW';

    /** the column name for the ACT_MASTER_DIAGRAM field */
    const ACT_MASTER_DIAGRAM = 'BPMN_ACTIVITY.ACT_MASTER_DIAGRAM';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('ActUid', 'PrjUid', 'ProUid', 'ActName', 'ActType', 'ActIsForCompensation', 'ActStartQuantity', 'ActCompletionQuantity', 'ActTaskType', 'ActImplementation', 'ActInstantiate', 'ActScriptType', 'ActScript', 'ActLoopType', 'ActTestBefore', 'ActLoopMaximum', 'ActLoopCondition', 'ActLoopCardinality', 'ActLoopBehavior', 'ActIsAdhoc', 'ActIsCollapsed', 'ActCompletionCondition', 'ActOrdering', 'ActCancelRemainingInstances', 'ActProtocol', 'ActMethod', 'ActIsGlobal', 'ActReferer', 'ActDefaultFlow', 'ActMasterDiagram', ),
        BasePeer::TYPE_COLNAME => array (BpmnActivityPeer::ACT_UID, BpmnActivityPeer::PRJ_UID, BpmnActivityPeer::PRO_UID, BpmnActivityPeer::ACT_NAME, BpmnActivityPeer::ACT_TYPE, BpmnActivityPeer::ACT_IS_FOR_COMPENSATION, BpmnActivityPeer::ACT_START_QUANTITY, BpmnActivityPeer::ACT_COMPLETION_QUANTITY, BpmnActivityPeer::ACT_TASK_TYPE, BpmnActivityPeer::ACT_IMPLEMENTATION, BpmnActivityPeer::ACT_INSTANTIATE, BpmnActivityPeer::ACT_SCRIPT_TYPE, BpmnActivityPeer::ACT_SCRIPT, BpmnActivityPeer::ACT_LOOP_TYPE, BpmnActivityPeer::ACT_TEST_BEFORE, BpmnActivityPeer::ACT_LOOP_MAXIMUM, BpmnActivityPeer::ACT_LOOP_CONDITION, BpmnActivityPeer::ACT_LOOP_CARDINALITY, BpmnActivityPeer::ACT_LOOP_BEHAVIOR, BpmnActivityPeer::ACT_IS_ADHOC, BpmnActivityPeer::ACT_IS_COLLAPSED, BpmnActivityPeer::ACT_COMPLETION_CONDITION, BpmnActivityPeer::ACT_ORDERING, BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES, BpmnActivityPeer::ACT_PROTOCOL, BpmnActivityPeer::ACT_METHOD, BpmnActivityPeer::ACT_IS_GLOBAL, BpmnActivityPeer::ACT_REFERER, BpmnActivityPeer::ACT_DEFAULT_FLOW, BpmnActivityPeer::ACT_MASTER_DIAGRAM, ),
        BasePeer::TYPE_FIELDNAME => array ('ACT_UID', 'PRJ_UID', 'PRO_UID', 'ACT_NAME', 'ACT_TYPE', 'ACT_IS_FOR_COMPENSATION', 'ACT_START_QUANTITY', 'ACT_COMPLETION_QUANTITY', 'ACT_TASK_TYPE', 'ACT_IMPLEMENTATION', 'ACT_INSTANTIATE', 'ACT_SCRIPT_TYPE', 'ACT_SCRIPT', 'ACT_LOOP_TYPE', 'ACT_TEST_BEFORE', 'ACT_LOOP_MAXIMUM', 'ACT_LOOP_CONDITION', 'ACT_LOOP_CARDINALITY', 'ACT_LOOP_BEHAVIOR', 'ACT_IS_ADHOC', 'ACT_IS_COLLAPSED', 'ACT_COMPLETION_CONDITION', 'ACT_ORDERING', 'ACT_CANCEL_REMAINING_INSTANCES', 'ACT_PROTOCOL', 'ACT_METHOD', 'ACT_IS_GLOBAL', 'ACT_REFERER', 'ACT_DEFAULT_FLOW', 'ACT_MASTER_DIAGRAM', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('ActUid' => 0, 'PrjUid' => 1, 'ProUid' => 2, 'ActName' => 3, 'ActType' => 4, 'ActIsForCompensation' => 5, 'ActStartQuantity' => 6, 'ActCompletionQuantity' => 7, 'ActTaskType' => 8, 'ActImplementation' => 9, 'ActInstantiate' => 10, 'ActScriptType' => 11, 'ActScript' => 12, 'ActLoopType' => 13, 'ActTestBefore' => 14, 'ActLoopMaximum' => 15, 'ActLoopCondition' => 16, 'ActLoopCardinality' => 17, 'ActLoopBehavior' => 18, 'ActIsAdhoc' => 19, 'ActIsCollapsed' => 20, 'ActCompletionCondition' => 21, 'ActOrdering' => 22, 'ActCancelRemainingInstances' => 23, 'ActProtocol' => 24, 'ActMethod' => 25, 'ActIsGlobal' => 26, 'ActReferer' => 27, 'ActDefaultFlow' => 28, 'ActMasterDiagram' => 29, ),
        BasePeer::TYPE_COLNAME => array (BpmnActivityPeer::ACT_UID => 0, BpmnActivityPeer::PRJ_UID => 1, BpmnActivityPeer::PRO_UID => 2, BpmnActivityPeer::ACT_NAME => 3, BpmnActivityPeer::ACT_TYPE => 4, BpmnActivityPeer::ACT_IS_FOR_COMPENSATION => 5, BpmnActivityPeer::ACT_START_QUANTITY => 6, BpmnActivityPeer::ACT_COMPLETION_QUANTITY => 7, BpmnActivityPeer::ACT_TASK_TYPE => 8, BpmnActivityPeer::ACT_IMPLEMENTATION => 9, BpmnActivityPeer::ACT_INSTANTIATE => 10, BpmnActivityPeer::ACT_SCRIPT_TYPE => 11, BpmnActivityPeer::ACT_SCRIPT => 12, BpmnActivityPeer::ACT_LOOP_TYPE => 13, BpmnActivityPeer::ACT_TEST_BEFORE => 14, BpmnActivityPeer::ACT_LOOP_MAXIMUM => 15, BpmnActivityPeer::ACT_LOOP_CONDITION => 16, BpmnActivityPeer::ACT_LOOP_CARDINALITY => 17, BpmnActivityPeer::ACT_LOOP_BEHAVIOR => 18, BpmnActivityPeer::ACT_IS_ADHOC => 19, BpmnActivityPeer::ACT_IS_COLLAPSED => 20, BpmnActivityPeer::ACT_COMPLETION_CONDITION => 21, BpmnActivityPeer::ACT_ORDERING => 22, BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES => 23, BpmnActivityPeer::ACT_PROTOCOL => 24, BpmnActivityPeer::ACT_METHOD => 25, BpmnActivityPeer::ACT_IS_GLOBAL => 26, BpmnActivityPeer::ACT_REFERER => 27, BpmnActivityPeer::ACT_DEFAULT_FLOW => 28, BpmnActivityPeer::ACT_MASTER_DIAGRAM => 29, ),
        BasePeer::TYPE_FIELDNAME => array ('ACT_UID' => 0, 'PRJ_UID' => 1, 'PRO_UID' => 2, 'ACT_NAME' => 3, 'ACT_TYPE' => 4, 'ACT_IS_FOR_COMPENSATION' => 5, 'ACT_START_QUANTITY' => 6, 'ACT_COMPLETION_QUANTITY' => 7, 'ACT_TASK_TYPE' => 8, 'ACT_IMPLEMENTATION' => 9, 'ACT_INSTANTIATE' => 10, 'ACT_SCRIPT_TYPE' => 11, 'ACT_SCRIPT' => 12, 'ACT_LOOP_TYPE' => 13, 'ACT_TEST_BEFORE' => 14, 'ACT_LOOP_MAXIMUM' => 15, 'ACT_LOOP_CONDITION' => 16, 'ACT_LOOP_CARDINALITY' => 17, 'ACT_LOOP_BEHAVIOR' => 18, 'ACT_IS_ADHOC' => 19, 'ACT_IS_COLLAPSED' => 20, 'ACT_COMPLETION_CONDITION' => 21, 'ACT_ORDERING' => 22, 'ACT_CANCEL_REMAINING_INSTANCES' => 23, 'ACT_PROTOCOL' => 24, 'ACT_METHOD' => 25, 'ACT_IS_GLOBAL' => 26, 'ACT_REFERER' => 27, 'ACT_DEFAULT_FLOW' => 28, 'ACT_MASTER_DIAGRAM' => 29, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnActivityMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnActivityMapBuilder');
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
            $map = BpmnActivityPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. BpmnActivityPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnActivityPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_UID);

        $criteria->addSelectColumn(BpmnActivityPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnActivityPeer::PRO_UID);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_NAME);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_TYPE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_IS_FOR_COMPENSATION);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_START_QUANTITY);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_COMPLETION_QUANTITY);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_TASK_TYPE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_IMPLEMENTATION);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_INSTANTIATE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_SCRIPT_TYPE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_SCRIPT);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_LOOP_TYPE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_TEST_BEFORE);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_LOOP_MAXIMUM);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_LOOP_CONDITION);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_LOOP_CARDINALITY);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_LOOP_BEHAVIOR);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_IS_ADHOC);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_IS_COLLAPSED);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_COMPLETION_CONDITION);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_ORDERING);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_PROTOCOL);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_METHOD);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_IS_GLOBAL);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_REFERER);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_DEFAULT_FLOW);

        $criteria->addSelectColumn(BpmnActivityPeer::ACT_MASTER_DIAGRAM);

    }

    const COUNT = 'COUNT(BPMN_ACTIVITY.ACT_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_ACTIVITY.ACT_UID)';

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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
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
     * @return     BpmnActivity
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnActivityPeer::doSelect($critcopy, $con);
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
        return BpmnActivityPeer::populateObjects(BpmnActivityPeer::doSelectRS($criteria, $con));
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
            BpmnActivityPeer::addSelectColumns($criteria);
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
        $cls = BpmnActivityPeer::getOMClass();
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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnActivity objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnActivity objects.
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

        BpmnActivityPeer::addSelectColumns($c);
        $startcol = (BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnActivityPeer::getOMClass();

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
                    $temp_obj2->addBpmnActivity($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnActivitys();
                $obj2->addBpmnActivity($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnActivity objects pre-filled with their BpmnProcess objects.
     *
     * @return     array Array of BpmnActivity objects.
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

        BpmnActivityPeer::addSelectColumns($c);
        $startcol = (BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProcessPeer::addSelectColumns($c);

        $c->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnActivityPeer::getOMClass();

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
                    $temp_obj2->addBpmnActivity($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnActivitys();
                $obj2->addBpmnActivity($obj1); //CHECKME
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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $criteria->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnActivity objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnActivity objects.
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

        BpmnActivityPeer::addSelectColumns($c);
        $startcol2 = (BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $c->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnActivityPeer::getOMClass();


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
                    $temp_obj2->addBpmnActivity($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnActivitys();
                $obj2->addBpmnActivity($obj1);
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
                    $temp_obj3->addBpmnActivity($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnActivitys();
                $obj3->addBpmnActivity($obj1);
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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnActivityPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnActivityPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnActivity objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnActivity objects.
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

        BpmnActivityPeer::addSelectColumns($c);
        $startcol2 = (BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnActivityPeer::PRO_UID, BpmnProcessPeer::PRO_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnActivityPeer::getOMClass();

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
                    $temp_obj2->addBpmnActivity($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnActivitys();
                $obj2->addBpmnActivity($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnActivity objects pre-filled with all related objects except BpmnProcess.
     *
     * @return     array Array of BpmnActivity objects.
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

        BpmnActivityPeer::addSelectColumns($c);
        $startcol2 = (BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnActivityPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnActivityPeer::getOMClass();

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
                    $temp_obj2->addBpmnActivity($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnActivitys();
                $obj2->addBpmnActivity($obj1);
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
        return BpmnActivityPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnActivity or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnActivity object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from BpmnActivity object
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
     * Method perform an UPDATE on the database, given a BpmnActivity or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnActivity object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(BpmnActivityPeer::ACT_UID);
            $selectCriteria->add(BpmnActivityPeer::ACT_UID, $criteria->remove(BpmnActivityPeer::ACT_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_ACTIVITY table.
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
            $affectedRows += BasePeer::doDeleteAll(BpmnActivityPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnActivity or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnActivity object or primary key or array of primary keys
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
            $con = Propel::getConnection(BpmnActivityPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnActivity) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnActivityPeer::ACT_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given BpmnActivity object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnActivity $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnActivity $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnActivityPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnActivityPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BpmnActivityPeer::DATABASE_NAME, BpmnActivityPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnActivity
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnActivityPeer::DATABASE_NAME);

        $criteria->add(BpmnActivityPeer::ACT_UID, $pk);


        $v = BpmnActivityPeer::doSelect($criteria, $con);

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
            $criteria->add(BpmnActivityPeer::ACT_UID, $pks, Criteria::IN);
            $objs = BpmnActivityPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnActivityPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnActivityMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnActivityMapBuilder');
}

