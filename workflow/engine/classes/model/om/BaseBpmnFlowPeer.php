<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnFlowPeer::getOMClass()
include_once 'classes/model/BpmnFlow.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_FLOW' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnFlowPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_FLOW';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnFlow';

    /** The total number of columns. */
    const NUM_COLUMNS = 19;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the FLO_UID field */
    const FLO_UID = 'BPMN_FLOW.FLO_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_FLOW.PRJ_UID';

    /** the column name for the DIA_UID field */
    const DIA_UID = 'BPMN_FLOW.DIA_UID';

    /** the column name for the FLO_TYPE field */
    const FLO_TYPE = 'BPMN_FLOW.FLO_TYPE';

    /** the column name for the FLO_NAME field */
    const FLO_NAME = 'BPMN_FLOW.FLO_NAME';

    /** the column name for the FLO_ELEMENT_ORIGIN field */
    const FLO_ELEMENT_ORIGIN = 'BPMN_FLOW.FLO_ELEMENT_ORIGIN';

    /** the column name for the FLO_ELEMENT_ORIGIN_TYPE field */
    const FLO_ELEMENT_ORIGIN_TYPE = 'BPMN_FLOW.FLO_ELEMENT_ORIGIN_TYPE';

    /** the column name for the FLO_ELEMENT_ORIGIN_PORT field */
    const FLO_ELEMENT_ORIGIN_PORT = 'BPMN_FLOW.FLO_ELEMENT_ORIGIN_PORT';

    /** the column name for the FLO_ELEMENT_DEST field */
    const FLO_ELEMENT_DEST = 'BPMN_FLOW.FLO_ELEMENT_DEST';

    /** the column name for the FLO_ELEMENT_DEST_TYPE field */
    const FLO_ELEMENT_DEST_TYPE = 'BPMN_FLOW.FLO_ELEMENT_DEST_TYPE';

    /** the column name for the FLO_ELEMENT_DEST_PORT field */
    const FLO_ELEMENT_DEST_PORT = 'BPMN_FLOW.FLO_ELEMENT_DEST_PORT';

    /** the column name for the FLO_IS_INMEDIATE field */
    const FLO_IS_INMEDIATE = 'BPMN_FLOW.FLO_IS_INMEDIATE';

    /** the column name for the FLO_CONDITION field */
    const FLO_CONDITION = 'BPMN_FLOW.FLO_CONDITION';

    /** the column name for the FLO_X1 field */
    const FLO_X1 = 'BPMN_FLOW.FLO_X1';

    /** the column name for the FLO_Y1 field */
    const FLO_Y1 = 'BPMN_FLOW.FLO_Y1';

    /** the column name for the FLO_X2 field */
    const FLO_X2 = 'BPMN_FLOW.FLO_X2';

    /** the column name for the FLO_Y2 field */
    const FLO_Y2 = 'BPMN_FLOW.FLO_Y2';

    /** the column name for the FLO_STATE field */
    const FLO_STATE = 'BPMN_FLOW.FLO_STATE';

    /** the column name for the FLO_POSITION field */
    const FLO_POSITION = 'BPMN_FLOW.FLO_POSITION';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('FloUid', 'PrjUid', 'DiaUid', 'FloType', 'FloName', 'FloElementOrigin', 'FloElementOriginType', 'FloElementOriginPort', 'FloElementDest', 'FloElementDestType', 'FloElementDestPort', 'FloIsInmediate', 'FloCondition', 'FloX1', 'FloY1', 'FloX2', 'FloY2', 'FloState', 'FloPosition', ),
        BasePeer::TYPE_COLNAME => array (BpmnFlowPeer::FLO_UID, BpmnFlowPeer::PRJ_UID, BpmnFlowPeer::DIA_UID, BpmnFlowPeer::FLO_TYPE, BpmnFlowPeer::FLO_NAME, BpmnFlowPeer::FLO_ELEMENT_ORIGIN, BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE, BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT, BpmnFlowPeer::FLO_ELEMENT_DEST, BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE, BpmnFlowPeer::FLO_ELEMENT_DEST_PORT, BpmnFlowPeer::FLO_IS_INMEDIATE, BpmnFlowPeer::FLO_CONDITION, BpmnFlowPeer::FLO_X1, BpmnFlowPeer::FLO_Y1, BpmnFlowPeer::FLO_X2, BpmnFlowPeer::FLO_Y2, BpmnFlowPeer::FLO_STATE, BpmnFlowPeer::FLO_POSITION, ),
        BasePeer::TYPE_FIELDNAME => array ('FLO_UID', 'PRJ_UID', 'DIA_UID', 'FLO_TYPE', 'FLO_NAME', 'FLO_ELEMENT_ORIGIN', 'FLO_ELEMENT_ORIGIN_TYPE', 'FLO_ELEMENT_ORIGIN_PORT', 'FLO_ELEMENT_DEST', 'FLO_ELEMENT_DEST_TYPE', 'FLO_ELEMENT_DEST_PORT', 'FLO_IS_INMEDIATE', 'FLO_CONDITION', 'FLO_X1', 'FLO_Y1', 'FLO_X2', 'FLO_Y2', 'FLO_STATE', 'FLO_POSITION', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('FloUid' => 0, 'PrjUid' => 1, 'DiaUid' => 2, 'FloType' => 3, 'FloName' => 4, 'FloElementOrigin' => 5, 'FloElementOriginType' => 6, 'FloElementOriginPort' => 7, 'FloElementDest' => 8, 'FloElementDestType' => 9, 'FloElementDestPort' => 10, 'FloIsInmediate' => 11, 'FloCondition' => 12, 'FloX1' => 13, 'FloY1' => 14, 'FloX2' => 15, 'FloY2' => 16, 'FloState' => 17, 'FloPosition' => 18, ),
        BasePeer::TYPE_COLNAME => array (BpmnFlowPeer::FLO_UID => 0, BpmnFlowPeer::PRJ_UID => 1, BpmnFlowPeer::DIA_UID => 2, BpmnFlowPeer::FLO_TYPE => 3, BpmnFlowPeer::FLO_NAME => 4, BpmnFlowPeer::FLO_ELEMENT_ORIGIN => 5, BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE => 6, BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT => 7, BpmnFlowPeer::FLO_ELEMENT_DEST => 8, BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE => 9, BpmnFlowPeer::FLO_ELEMENT_DEST_PORT => 10, BpmnFlowPeer::FLO_IS_INMEDIATE => 11, BpmnFlowPeer::FLO_CONDITION => 12, BpmnFlowPeer::FLO_X1 => 13, BpmnFlowPeer::FLO_Y1 => 14, BpmnFlowPeer::FLO_X2 => 15, BpmnFlowPeer::FLO_Y2 => 16, BpmnFlowPeer::FLO_STATE => 17, BpmnFlowPeer::FLO_POSITION => 18, ),
        BasePeer::TYPE_FIELDNAME => array ('FLO_UID' => 0, 'PRJ_UID' => 1, 'DIA_UID' => 2, 'FLO_TYPE' => 3, 'FLO_NAME' => 4, 'FLO_ELEMENT_ORIGIN' => 5, 'FLO_ELEMENT_ORIGIN_TYPE' => 6, 'FLO_ELEMENT_ORIGIN_PORT' => 7, 'FLO_ELEMENT_DEST' => 8, 'FLO_ELEMENT_DEST_TYPE' => 9, 'FLO_ELEMENT_DEST_PORT' => 10, 'FLO_IS_INMEDIATE' => 11, 'FLO_CONDITION' => 12, 'FLO_X1' => 13, 'FLO_Y1' => 14, 'FLO_X2' => 15, 'FLO_Y2' => 16, 'FLO_STATE' => 17, 'FLO_POSITION' => 18, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnFlowMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnFlowMapBuilder');
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
            $map = BpmnFlowPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. BpmnFlowPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnFlowPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_UID);

        $criteria->addSelectColumn(BpmnFlowPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnFlowPeer::DIA_UID);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_TYPE);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_NAME);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_ORIGIN);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_DEST);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_ELEMENT_DEST_PORT);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_IS_INMEDIATE);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_CONDITION);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_X1);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_Y1);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_X2);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_Y2);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_STATE);

        $criteria->addSelectColumn(BpmnFlowPeer::FLO_POSITION);

    }

    const COUNT = 'COUNT(BPMN_FLOW.FLO_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_FLOW.FLO_UID)';

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
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
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
     * @return     BpmnFlow
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnFlowPeer::doSelect($critcopy, $con);
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
        return BpmnFlowPeer::populateObjects(BpmnFlowPeer::doSelectRS($criteria, $con));
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
            BpmnFlowPeer::addSelectColumns($criteria);
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
        $cls = BpmnFlowPeer::getOMClass();
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
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Returns the number of rows matching criteria, joining the related BpmnDiagram table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinBpmnDiagram(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnFlow objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnFlow objects.
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

        BpmnFlowPeer::addSelectColumns($c);
        $startcol = (BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnFlowPeer::getOMClass();

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
                    $temp_obj2->addBpmnFlow($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnFlows();
                $obj2->addBpmnFlow($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnFlow objects pre-filled with their BpmnDiagram objects.
     *
     * @return     array Array of BpmnFlow objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinBpmnDiagram(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnFlowPeer::addSelectColumns($c);
        $startcol = (BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnDiagramPeer::addSelectColumns($c);

        $c->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnFlowPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnDiagramPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj2 = new $cls();
            $obj2->hydrate($rs, $startcol);

            $newObject = true;
            foreach($results as $temp_obj1) {
                $temp_obj2 = $temp_obj1->getBpmnDiagram(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    // e.g. $author->addBookRelatedByBookId()
                    $temp_obj2->addBpmnFlow($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnFlows();
                $obj2->addBpmnFlow($obj1); //CHECKME
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
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $criteria->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnFlow objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnFlow objects.
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

        BpmnFlowPeer::addSelectColumns($c);
        $startcol2 = (BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        BpmnDiagramPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnDiagramPeer::NUM_COLUMNS;

        $c->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $c->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnFlowPeer::getOMClass();


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
                    $temp_obj2->addBpmnFlow($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnFlows();
                $obj2->addBpmnFlow($obj1);
            }


                // Add objects for joined BpmnDiagram rows
    
            $omClass = BpmnDiagramPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj3 = new $cls();
            $obj3->hydrate($rs, $startcol3);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj3 = $temp_obj1->getBpmnDiagram(); // CHECKME
                if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj3->addBpmnFlow($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnFlows();
                $obj3->addBpmnFlow($obj1);
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
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Returns the number of rows matching criteria, joining the related BpmnDiagram table
     *
     * @param      Criteria $c
     * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
     * @param      Connection $con
     * @return     int Number of matching rows.
     */
    public static function doCountJoinAllExceptBpmnDiagram(Criteria $criteria, $distinct = false, $con = null)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // clear out anything that might confuse the ORDER BY clause
        $criteria->clearSelectColumns()->clearOrderByColumns();
        if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnFlowPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnFlowPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnFlow objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnFlow objects.
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

        BpmnFlowPeer::addSelectColumns($c);
        $startcol2 = (BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnDiagramPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnDiagramPeer::NUM_COLUMNS;

        $c->addJoin(BpmnFlowPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnFlowPeer::getOMClass();

            $cls = Propel::import($omClass);
            $obj1 = new $cls();
            $obj1->hydrate($rs);

            $omClass = BpmnDiagramPeer::getOMClass();


            $cls = Propel::import($omClass);
            $obj2  = new $cls();
            $obj2->hydrate($rs, $startcol2);

            $newObject = true;
            for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
                $temp_obj1 = $results[$j];
                $temp_obj2 = $temp_obj1->getBpmnDiagram(); //CHECKME
                if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
                    $newObject = false;
                    $temp_obj2->addBpmnFlow($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnFlows();
                $obj2->addBpmnFlow($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnFlow objects pre-filled with all related objects except BpmnDiagram.
     *
     * @return     array Array of BpmnFlow objects.
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptBpmnDiagram(Criteria $c, $con = null)
    {
        $c = clone $c;

        // Set the correct dbName if it has not been overridden
        // $c->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($c->getDbName() == Propel::getDefaultDB()) {
            $c->setDbName(self::DATABASE_NAME);
        }

        BpmnFlowPeer::addSelectColumns($c);
        $startcol2 = (BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnFlowPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnFlowPeer::getOMClass();

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
                    $temp_obj2->addBpmnFlow($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnFlows();
                $obj2->addBpmnFlow($obj1);
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
        return BpmnFlowPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnFlow or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnFlow object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from BpmnFlow object
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
     * Method perform an UPDATE on the database, given a BpmnFlow or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnFlow object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(BpmnFlowPeer::FLO_UID);
            $selectCriteria->add(BpmnFlowPeer::FLO_UID, $criteria->remove(BpmnFlowPeer::FLO_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_FLOW table.
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
            $affectedRows += BasePeer::doDeleteAll(BpmnFlowPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnFlow or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnFlow object or primary key or array of primary keys
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
            $con = Propel::getConnection(BpmnFlowPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnFlow) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnFlowPeer::FLO_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given BpmnFlow object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnFlow $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnFlow $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnFlowPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnFlowPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BpmnFlowPeer::DATABASE_NAME, BpmnFlowPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnFlow
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnFlowPeer::DATABASE_NAME);

        $criteria->add(BpmnFlowPeer::FLO_UID, $pk);


        $v = BpmnFlowPeer::doSelect($criteria, $con);

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
            $criteria->add(BpmnFlowPeer::FLO_UID, $pks, Criteria::IN);
            $objs = BpmnFlowPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnFlowPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnFlowMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnFlowMapBuilder');
}

