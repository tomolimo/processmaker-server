<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by BpmnBoundPeer::getOMClass()
include_once 'classes/model/BpmnBound.php';

/**
 * Base static class for performing query and update operations on the 'BPMN_BOUND' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnBoundPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'BPMN_BOUND';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.BpmnBound';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the BOU_UID field */
    const BOU_UID = 'BPMN_BOUND.BOU_UID';

    /** the column name for the PRJ_UID field */
    const PRJ_UID = 'BPMN_BOUND.PRJ_UID';

    /** the column name for the DIA_UID field */
    const DIA_UID = 'BPMN_BOUND.DIA_UID';

    /** the column name for the ELEMENT_UID field */
    const ELEMENT_UID = 'BPMN_BOUND.ELEMENT_UID';

    /** the column name for the BOU_ELEMENT field */
    const BOU_ELEMENT = 'BPMN_BOUND.BOU_ELEMENT';

    /** the column name for the BOU_ELEMENT_TYPE field */
    const BOU_ELEMENT_TYPE = 'BPMN_BOUND.BOU_ELEMENT_TYPE';

    /** the column name for the BOU_X field */
    const BOU_X = 'BPMN_BOUND.BOU_X';

    /** the column name for the BOU_Y field */
    const BOU_Y = 'BPMN_BOUND.BOU_Y';

    /** the column name for the BOU_WIDTH field */
    const BOU_WIDTH = 'BPMN_BOUND.BOU_WIDTH';

    /** the column name for the BOU_HEIGHT field */
    const BOU_HEIGHT = 'BPMN_BOUND.BOU_HEIGHT';

    /** the column name for the BOU_REL_POSITION field */
    const BOU_REL_POSITION = 'BPMN_BOUND.BOU_REL_POSITION';

    /** the column name for the BOU_SIZE_IDENTICAL field */
    const BOU_SIZE_IDENTICAL = 'BPMN_BOUND.BOU_SIZE_IDENTICAL';

    /** the column name for the BOU_CONTAINER field */
    const BOU_CONTAINER = 'BPMN_BOUND.BOU_CONTAINER';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('BouUid', 'PrjUid', 'DiaUid', 'ElementUid', 'BouElement', 'BouElementType', 'BouX', 'BouY', 'BouWidth', 'BouHeight', 'BouRelPosition', 'BouSizeIdentical', 'BouContainer', ),
        BasePeer::TYPE_COLNAME => array (BpmnBoundPeer::BOU_UID, BpmnBoundPeer::PRJ_UID, BpmnBoundPeer::DIA_UID, BpmnBoundPeer::ELEMENT_UID, BpmnBoundPeer::BOU_ELEMENT, BpmnBoundPeer::BOU_ELEMENT_TYPE, BpmnBoundPeer::BOU_X, BpmnBoundPeer::BOU_Y, BpmnBoundPeer::BOU_WIDTH, BpmnBoundPeer::BOU_HEIGHT, BpmnBoundPeer::BOU_REL_POSITION, BpmnBoundPeer::BOU_SIZE_IDENTICAL, BpmnBoundPeer::BOU_CONTAINER, ),
        BasePeer::TYPE_FIELDNAME => array ('BOU_UID', 'PRJ_UID', 'DIA_UID', 'ELEMENT_UID', 'BOU_ELEMENT', 'BOU_ELEMENT_TYPE', 'BOU_X', 'BOU_Y', 'BOU_WIDTH', 'BOU_HEIGHT', 'BOU_REL_POSITION', 'BOU_SIZE_IDENTICAL', 'BOU_CONTAINER', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('BouUid' => 0, 'PrjUid' => 1, 'DiaUid' => 2, 'ElementUid' => 3, 'BouElement' => 4, 'BouElementType' => 5, 'BouX' => 6, 'BouY' => 7, 'BouWidth' => 8, 'BouHeight' => 9, 'BouRelPosition' => 10, 'BouSizeIdentical' => 11, 'BouContainer' => 12, ),
        BasePeer::TYPE_COLNAME => array (BpmnBoundPeer::BOU_UID => 0, BpmnBoundPeer::PRJ_UID => 1, BpmnBoundPeer::DIA_UID => 2, BpmnBoundPeer::ELEMENT_UID => 3, BpmnBoundPeer::BOU_ELEMENT => 4, BpmnBoundPeer::BOU_ELEMENT_TYPE => 5, BpmnBoundPeer::BOU_X => 6, BpmnBoundPeer::BOU_Y => 7, BpmnBoundPeer::BOU_WIDTH => 8, BpmnBoundPeer::BOU_HEIGHT => 9, BpmnBoundPeer::BOU_REL_POSITION => 10, BpmnBoundPeer::BOU_SIZE_IDENTICAL => 11, BpmnBoundPeer::BOU_CONTAINER => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('BOU_UID' => 0, 'PRJ_UID' => 1, 'DIA_UID' => 2, 'ELEMENT_UID' => 3, 'BOU_ELEMENT' => 4, 'BOU_ELEMENT_TYPE' => 5, 'BOU_X' => 6, 'BOU_Y' => 7, 'BOU_WIDTH' => 8, 'BOU_HEIGHT' => 9, 'BOU_REL_POSITION' => 10, 'BOU_SIZE_IDENTICAL' => 11, 'BOU_CONTAINER' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/BpmnBoundMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.BpmnBoundMapBuilder');
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
            $map = BpmnBoundPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. BpmnBoundPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BpmnBoundPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_UID);

        $criteria->addSelectColumn(BpmnBoundPeer::PRJ_UID);

        $criteria->addSelectColumn(BpmnBoundPeer::DIA_UID);

        $criteria->addSelectColumn(BpmnBoundPeer::ELEMENT_UID);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_ELEMENT);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_ELEMENT_TYPE);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_X);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_Y);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_WIDTH);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_HEIGHT);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_REL_POSITION);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_SIZE_IDENTICAL);

        $criteria->addSelectColumn(BpmnBoundPeer::BOU_CONTAINER);

    }

    const COUNT = 'COUNT(BPMN_BOUND.BOU_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT BPMN_BOUND.BOU_UID)';

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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
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
     * @return     BpmnBound
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BpmnBoundPeer::doSelect($critcopy, $con);
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
        return BpmnBoundPeer::populateObjects(BpmnBoundPeer::doSelectRS($criteria, $con));
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
            BpmnBoundPeer::addSelectColumns($criteria);
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
        $cls = BpmnBoundPeer::getOMClass();
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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnBound objects pre-filled with their BpmnProject objects.
     *
     * @return     array Array of BpmnBound objects.
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

        BpmnBoundPeer::addSelectColumns($c);
        $startcol = (BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnProjectPeer::addSelectColumns($c);

        $c->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnBoundPeer::getOMClass();

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
                    $temp_obj2->addBpmnBound($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnBounds();
                $obj2->addBpmnBound($obj1); //CHECKME
            }
            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnBound objects pre-filled with their BpmnDiagram objects.
     *
     * @return     array Array of BpmnBound objects.
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

        BpmnBoundPeer::addSelectColumns($c);
        $startcol = (BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
        BpmnDiagramPeer::addSelectColumns($c);

        $c->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);
        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnBoundPeer::getOMClass();

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
                    $temp_obj2->addBpmnBound($obj1); //CHECKME
                    break;
                }
            }
            if ($newObject) {
                $obj2->initBpmnBounds();
                $obj2->addBpmnBound($obj1); //CHECKME
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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $criteria->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnBound objects pre-filled with all related objects.
     *
     * @return     array Array of BpmnBound objects.
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

        BpmnBoundPeer::addSelectColumns($c);
        $startcol2 = (BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        BpmnDiagramPeer::addSelectColumns($c);
        $startcol4 = $startcol3 + BpmnDiagramPeer::NUM_COLUMNS;

        $c->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $c->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnBoundPeer::getOMClass();


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
                    $temp_obj2->addBpmnBound($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnBounds();
                $obj2->addBpmnBound($obj1);
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
                    $temp_obj3->addBpmnBound($obj1); // CHECKME
                    break;
                }
            }

            if ($newObject) {
                $obj3->initBpmnBounds();
                $obj3->addBpmnBound($obj1);
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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
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
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(BpmnBoundPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach($criteria->getGroupByColumns() as $column)
        {
            $criteria->addSelectColumn($column);
        }

        $criteria->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);

        $rs = BpmnBoundPeer::doSelectRS($criteria, $con);
        if ($rs->next()) {
            return $rs->getInt(1);
        } else {
            // no rows returned; we infer that means 0 matches.
            return 0;
        }
    }


    /**
     * Selects a collection of BpmnBound objects pre-filled with all related objects except BpmnProject.
     *
     * @return     array Array of BpmnBound objects.
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

        BpmnBoundPeer::addSelectColumns($c);
        $startcol2 = (BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnDiagramPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnDiagramPeer::NUM_COLUMNS;

        $c->addJoin(BpmnBoundPeer::DIA_UID, BpmnDiagramPeer::DIA_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnBoundPeer::getOMClass();

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
                    $temp_obj2->addBpmnBound($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnBounds();
                $obj2->addBpmnBound($obj1);
            }

            $results[] = $obj1;
        }
        return $results;
    }


    /**
     * Selects a collection of BpmnBound objects pre-filled with all related objects except BpmnDiagram.
     *
     * @return     array Array of BpmnBound objects.
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

        BpmnBoundPeer::addSelectColumns($c);
        $startcol2 = (BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

        BpmnProjectPeer::addSelectColumns($c);
        $startcol3 = $startcol2 + BpmnProjectPeer::NUM_COLUMNS;

        $c->addJoin(BpmnBoundPeer::PRJ_UID, BpmnProjectPeer::PRJ_UID);


        $rs = BasePeer::doSelect($c, $con);
        $results = array();

        while($rs->next()) {

            $omClass = BpmnBoundPeer::getOMClass();

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
                    $temp_obj2->addBpmnBound($obj1);
                    break;
                }
            }

            if ($newObject) {
                $obj2->initBpmnBounds();
                $obj2->addBpmnBound($obj1);
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
        return BpmnBoundPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a BpmnBound or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnBound object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from BpmnBound object
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
     * Method perform an UPDATE on the database, given a BpmnBound or Criteria object.
     *
     * @param      mixed $values Criteria or BpmnBound object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(BpmnBoundPeer::BOU_UID);
            $selectCriteria->add(BpmnBoundPeer::BOU_UID, $criteria->remove(BpmnBoundPeer::BOU_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the BPMN_BOUND table.
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
            $affectedRows += BasePeer::doDeleteAll(BpmnBoundPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a BpmnBound or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BpmnBound object or primary key or array of primary keys
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
            $con = Propel::getConnection(BpmnBoundPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof BpmnBound) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(BpmnBoundPeer::BOU_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given BpmnBound object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      BpmnBound $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(BpmnBound $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BpmnBoundPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BpmnBoundPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BpmnBoundPeer::DATABASE_NAME, BpmnBoundPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     BpmnBound
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(BpmnBoundPeer::DATABASE_NAME);

        $criteria->add(BpmnBoundPeer::BOU_UID, $pk);


        $v = BpmnBoundPeer::doSelect($criteria, $con);

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
            $criteria->add(BpmnBoundPeer::BOU_UID, $pks, Criteria::IN);
            $objs = BpmnBoundPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseBpmnBoundPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/BpmnBoundMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.BpmnBoundMapBuilder');
}

