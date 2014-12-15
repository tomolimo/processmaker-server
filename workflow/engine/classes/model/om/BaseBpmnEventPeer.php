<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnEventPeer::getOMClass()
include_once 'classes/model/BpmnEvent.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_EVENT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnEventPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_EVENT';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnEvent';

    /** The total number of columns. */
    const NUM_COLUMNS = 23;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the EVN_UID field */
    const EVN_UID = 'BPMN_EVENT.EVN_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_EVENT.PRJ_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'BPMN_EVENT.PRO_UID';

    /** the column name for the EVN_NAME field */
    const EVN_NAME = 'BPMN_EVENT.EVN_NAME';

    /** the column name for the EVN_TYPE field */
    const EVN_TYPE = 'BPMN_EVENT.EVN_TYPE';

    /** the column name for the EVN_MARKER field */
    const EVN_MARKER = 'BPMN_EVENT.EVN_MARKER';

    /** the column name for the EVN_IS_INTERRUPTING field */
    const EVN_IS_INTERRUPTING = 'BPMN_EVENT.EVN_IS_INTERRUPTING';

    /** the column name for the EVN_ATTACHED_TO field */
    const EVN_ATTACHED_TO = 'BPMN_EVENT.EVN_ATTACHED_TO';

    /** the column name for the EVN_CANCEL_ACTIVITY field */
    const EVN_CANCEL_ACTIVITY = 'BPMN_EVENT.EVN_CANCEL_ACTIVITY';

    /** the column name for the EVN_ACTIVITY_REF field */
    const EVN_ACTIVITY_REF = 'BPMN_EVENT.EVN_ACTIVITY_REF';

    /** the column name for the EVN_WAIT_FOR_COMPLETION field */
    const EVN_WAIT_FOR_COMPLETION = 'BPMN_EVENT.EVN_WAIT_FOR_COMPLETION';

    /** the column name for the EVN_ERROR_NAME field */
    const EVN_ERROR_NAME = 'BPMN_EVENT.EVN_ERROR_NAME';

    /** the column name for the EVN_ERROR_CODE field */
    const EVN_ERROR_CODE = 'BPMN_EVENT.EVN_ERROR_CODE';

    /** the column name for the EVN_ESCALATION_NAME field */
    const EVN_ESCALATION_NAME = 'BPMN_EVENT.EVN_ESCALATION_NAME';

    /** the column name for the EVN_ESCALATION_CODE field */
    const EVN_ESCALATION_CODE = 'BPMN_EVENT.EVN_ESCALATION_CODE';

    /** the column name for the EVN_CONDITION field */
    const EVN_CONDITION = 'BPMN_EVENT.EVN_CONDITION';

    /** the column name for the EVN_MESSAGE field */
    const EVN_MESSAGE = 'BPMN_EVENT.EVN_MESSAGE';

    /** the column name for the EVN_OPERATION_NAME field */
    const EVN_OPERATION_NAME = 'BPMN_EVENT.EVN_OPERATION_NAME';

    /** the column name for the EVN_OPERATION_IMPLEMENTATION_REF field */
    const EVN_OPERATION_IMPLEMENTATION_REF = 'BPMN_EVENT.EVN_OPERATION_IMPLEMENTATION_REF';

    /** the column name for the EVN_TIME_DATE field */
    const EVN_TIME_DATE = 'BPMN_EVENT.EVN_TIME_DATE';

    /** the column name for the EVN_TIME_CYCLE field */
    const EVN_TIME_CYCLE = 'BPMN_EVENT.EVN_TIME_CYCLE';

    /** the column name for the EVN_TIME_DURATION field */
    const EVN_TIME_DURATION = 'BPMN_EVENT.EVN_TIME_DURATION';

    /** the column name for the EVN_BEHAVIOR field */
    const EVN_BEHAVIOR = 'BPMN_EVENT.EVN_BEHAVIOR';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('EvnUid', 'PrjUid', 'ProUid', 'EvnName', 'EvnType', 'EvnMarker', 'EvnIsInterrupting', 'EvnAttachedTo', 'EvnCancelActivity', 'EvnActivityRef', 'EvnWaitForCompletion', 'EvnErrorName', 'EvnErrorCode', 'EvnEscalationName', 'EvnEscalationCode', 'EvnCondition', 'EvnMessage', 'EvnOperationName', 'EvnOperationImplementationRef', 'EvnTimeDate', 'EvnTimeCycle', 'EvnTimeDuration', 'EvnBehavior', ),
        BasePeer::TYPE_COLNAME => array (BpmnEventPeer::EVN_UID, BpmnEventPeer::PRJ_UID, BpmnEventPeer::PRO_UID, BpmnEventPeer::EVN_NAME, BpmnEventPeer::EVN_TYPE, BpmnEventPeer::EVN_MARKER, BpmnEventPeer::EVN_IS_INTERRUPTING, BpmnEventPeer::EVN_ATTACHED_TO, BpmnEventPeer::EVN_CANCEL_ACTIVITY, BpmnEventPeer::EVN_ACTIVITY_REF, BpmnEventPeer::EVN_WAIT_FOR_COMPLETION, BpmnEventPeer::EVN_ERROR_NAME, BpmnEventPeer::EVN_ERROR_CODE, BpmnEventPeer::EVN_ESCALATION_NAME, BpmnEventPeer::EVN_ESCALATION_CODE, BpmnEventPeer::EVN_CONDITION, BpmnEventPeer::EVN_MESSAGE, BpmnEventPeer::EVN_OPERATION_NAME, BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF, BpmnEventPeer::EVN_TIME_DATE, BpmnEventPeer::EVN_TIME_CYCLE, BpmnEventPeer::EVN_TIME_DURATION, BpmnEventPeer::EVN_BEHAVIOR, ),
        BasePeer::TYPE_FIELDNAME => array ('EVN_UID', 'PRJ_UID', 'PRO_UID', 'EVN_NAME', 'EVN_TYPE', 'EVN_MARKER', 'EVN_IS_INTERRUPTING', 'EVN_ATTACHED_TO', 'EVN_CANCEL_ACTIVITY', 'EVN_ACTIVITY_REF', 'EVN_WAIT_FOR_COMPLETION', 'EVN_ERROR_NAME', 'EVN_ERROR_CODE', 'EVN_ESCALATION_NAME', 'EVN_ESCALATION_CODE', 'EVN_CONDITION', 'EVN_MESSAGE', 'EVN_OPERATION_NAME', 'EVN_OPERATION_IMPLEMENTATION_REF', 'EVN_TIME_DATE', 'EVN_TIME_CYCLE', 'EVN_TIME_DURATION', 'EVN_BEHAVIOR', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('EvnUid' => 0, 'PrjUid' => 1, 'ProUid' => 2, 'EvnName' => 3, 'EvnType' => 4, 'EvnMarker' => 5, 'EvnIsInterrupting' => 6, 'EvnAttachedTo' => 7, 'EvnCancelActivity' => 8, 'EvnActivityRef' => 9, 'EvnWaitForCompletion' => 10, 'EvnErrorName' => 11, 'EvnErrorCode' => 12, 'EvnEscalationName' => 13, 'EvnEscalationCode' => 14, 'EvnCondition' => 15, 'EvnMessage' => 16, 'EvnOperationName' => 17, 'EvnOperationImplementationRef' => 18, 'EvnTimeDate' => 19, 'EvnTimeCycle' => 20, 'EvnTimeDuration' => 21, 'EvnBehavior' => 22, ),
        BasePeer::TYPE_COLNAME => array (BpmnEventPeer::EVN_UID => 0, BpmnEventPeer::PRJ_UID => 1, BpmnEventPeer::PRO_UID => 2, BpmnEventPeer::EVN_NAME => 3, BpmnEventPeer::EVN_TYPE => 4, BpmnEventPeer::EVN_MARKER => 5, BpmnEventPeer::EVN_IS_INTERRUPTING => 6, BpmnEventPeer::EVN_ATTACHED_TO => 7, BpmnEventPeer::EVN_CANCEL_ACTIVITY => 8, BpmnEventPeer::EVN_ACTIVITY_REF => 9, BpmnEventPeer::EVN_WAIT_FOR_COMPLETION => 10, BpmnEventPeer::EVN_ERROR_NAME => 11, BpmnEventPeer::EVN_ERROR_CODE => 12, BpmnEventPeer::EVN_ESCALATION_NAME => 13, BpmnEventPeer::EVN_ESCALATION_CODE => 14, BpmnEventPeer::EVN_CONDITION => 15, BpmnEventPeer::EVN_MESSAGE => 16, BpmnEventPeer::EVN_OPERATION_NAME => 17, BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF => 18, BpmnEventPeer::EVN_TIME_DATE => 19, BpmnEventPeer::EVN_TIME_CYCLE => 20, BpmnEventPeer::EVN_TIME_DURATION => 21, BpmnEventPeer::EVN_BEHAVIOR => 22, ),
        BasePeer::TYPE_FIELDNAME => array ('EVN_UID' => 0, 'PRJ_UID' => 1, 'PRO_UID' => 2, 'EVN_NAME' => 3, 'EVN_TYPE' => 4, 'EVN_MARKER' => 5, 'EVN_IS_INTERRUPTING' => 6, 'EVN_ATTACHED_TO' => 7, 'EVN_CANCEL_ACTIVITY' => 8, 'EVN_ACTIVITY_REF' => 9, 'EVN_WAIT_FOR_COMPLETION' => 10, 'EVN_ERROR_NAME' => 11, 'EVN_ERROR_CODE' => 12, 'EVN_ESCALATION_NAME' => 13, 'EVN_ESCALATION_CODE' => 14, 'EVN_CONDITION' => 15, 'EVN_MESSAGE' => 16, 'EVN_OPERATION_NAME' => 17, 'EVN_OPERATION_IMPLEMENTATION_REF' => 18, 'EVN_TIME_DATE' => 19, 'EVN_TIME_CYCLE' => 20, 'EVN_TIME_DURATION' => 21, 'EVN_BEHAVIOR' => 22, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnEventMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnEventMapBuilder');
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
            $map = BpmnEventPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. BpmnEventPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnEventPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(BpmnEventPeer::EVN_UID);

        $criteria->addSelectColumn(BpmnEventPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnEventPeer::PRO_UID);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_NAME);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_TYPE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_MARKER);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_IS_INTERRUPTING);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ATTACHED_TO);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_CANCEL_ACTIVITY);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ACTIVITY_REF);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_WAIT_FOR_COMPLETION);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ERROR_NAME);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ERROR_CODE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ESCALATION_NAME);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_ESCALATION_CODE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_CONDITION);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_MESSAGE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_OPERATION_NAME);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_TIME_DATE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_TIME_CYCLE);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_TIME_DURATION);

        $criteria->addSelectColumn(BpmnEventPeer::EVN_BEHAVIOR);

    }

    const COUNT = 'COUNT(BPMN_EVENT.EVN_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_EVENT.EVN_UID)';

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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
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
     * @return     BpmnEvent
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnEventPeer::doSelect($critcopy, $con);
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
        return BpmnEventPeer::populateObjects(BpmnEventPeer::doSelectRS($criteria, $con));
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
            BpmnEventPeer::addSelectColumns($criteria);
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
        $cls = BpmnEventPeer::getOMClass();
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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnEvent objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnEvent objects.
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

        BpmnEventPeer::addSelectColumns($c);
        $startcol = (BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnEventPeer::getOMClass();

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
                    $temp_obj2->addBpmnEvent($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnEvents();
                $obj2->addBpmnEvent($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnEvent objects pre-filled with their BpmnProcess objects.
     *
     * @return     array Array of BpmnEvent objects.
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

        BpmnEventPeer::addSelectColumns($c);
        $startcol = (BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProcessPeer::addSelectColumns($c);

        $c->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnEventPeer::getOMClass();

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
                    $temp_obj2->addBpmnEvent($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnEvents();
                $obj2->addBpmnEvent($obj1); //CHECKME
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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $criteria->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnEvent objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnEvent objects.
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

        BpmnEventPeer::addSelectColumns($c);
        $startcol2 = (BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $c->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnEventPeer::getOMClass();


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
                    $temp_obj2->addBpmnEvent($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnEvents();
                $obj2->addBpmnEvent($obj1);
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
                    $temp_obj3->addBpmnEvent($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnEvents();
                $obj3->addBpmnEvent($obj1);
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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnEventPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnEventPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnEventPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnEvent objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnEvent objects.
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

        BpmnEventPeer::addSelectColumns($c);
        $startcol2 = (BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProcessPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProcessPeer::NUM_COLUMNS;

        $c->addJoin(BpmnEventPeer::PRO_UID, BpmnProcessPeer::PRO_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnEventPeer::getOMClass();

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
                    $temp_obj2->addBpmnEvent($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnEvents();
                $obj2->addBpmnEvent($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnEvent objects pre-filled with all related objects except BpmnProcess.
     *
     * @return     array Array of BpmnEvent objects.
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

        BpmnEventPeer::addSelectColumns($c);
        $startcol2 = (BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnEventPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnEventPeer::getOMClass();

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
                    $temp_obj2->addBpmnEvent($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnEvents();
                $obj2->addBpmnEvent($obj1);
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
        return BpmnEventPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnEvent or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnEvent object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from BpmnEvent object
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
     * Method perform an UPDATE on the database, given a BpmnEvent or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnEvent object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(BpmnEventPeer::EVN_UID);
            $selectCriteria->add(BpmnEventPeer::EVN_UID, $criteria->remove(BpmnEventPeer::EVN_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_EVENT table.
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
            $affectedRows += BasePeer::doDeleteAll(BpmnEventPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnEvent or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnEvent object or primary key or array of primary keys
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
            $con = Propel::getConnection(BpmnEventPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnEvent) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnEventPeer::EVN_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given BpmnEvent object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnEvent $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnEvent $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnEventPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnEventPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BpmnEventPeer::DATABASE_NAME, BpmnEventPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnEvent
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnEventPeer::DATABASE_NAME);

        $criteria->add(BpmnEventPeer::EVN_UID, $pk);


        $v = BpmnEventPeer::doSelect($criteria, $con);

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
            $criteria->add(BpmnEventPeer::EVN_UID, $pks, Criteria::IN);
            $objs = BpmnEventPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnEventPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnEventMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnEventMapBuilder');
}

