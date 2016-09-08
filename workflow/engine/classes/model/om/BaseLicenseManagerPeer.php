<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by LicenseManagerPeer::getOMClass()
include_once 'classes/model/LicenseManager.php';

/**
 * Base static class for performing query and update operations on the 'LICENSE_MANAGER' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseLicenseManagerPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'LICENSE_MANAGER';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.LicenseManager';

    /** The total number of columns. */
    const NUM_COLUMNS = 10;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the LICENSE_UID field */
    const LICENSE_UID = 'LICENSE_MANAGER.LICENSE_UID';

    /** the column name for the LICENSE_USER field */
    const LICENSE_USER = 'LICENSE_MANAGER.LICENSE_USER';

    /** the column name for the LICENSE_START field */
    const LICENSE_START = 'LICENSE_MANAGER.LICENSE_START';

    /** the column name for the LICENSE_END field */
    const LICENSE_END = 'LICENSE_MANAGER.LICENSE_END';

    /** the column name for the LICENSE_SPAN field */
    const LICENSE_SPAN = 'LICENSE_MANAGER.LICENSE_SPAN';

    /** the column name for the LICENSE_STATUS field */
    const LICENSE_STATUS = 'LICENSE_MANAGER.LICENSE_STATUS';

    /** the column name for the LICENSE_DATA field */
    const LICENSE_DATA = 'LICENSE_MANAGER.LICENSE_DATA';

    /** the column name for the LICENSE_PATH field */
    const LICENSE_PATH = 'LICENSE_MANAGER.LICENSE_PATH';

    /** the column name for the LICENSE_WORKSPACE field */
    const LICENSE_WORKSPACE = 'LICENSE_MANAGER.LICENSE_WORKSPACE';

    /** the column name for the LICENSE_TYPE field */
    const LICENSE_TYPE = 'LICENSE_MANAGER.LICENSE_TYPE';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('LicenseUid', 'LicenseUser', 'LicenseStart', 'LicenseEnd', 'LicenseSpan', 'LicenseStatus', 'LicenseData', 'LicensePath', 'LicenseWorkspace', 'LicenseType', ),
        BasePeer::TYPE_COLNAME => array (LicenseManagerPeer::LICENSE_UID, LicenseManagerPeer::LICENSE_USER, LicenseManagerPeer::LICENSE_START, LicenseManagerPeer::LICENSE_END, LicenseManagerPeer::LICENSE_SPAN, LicenseManagerPeer::LICENSE_STATUS, LicenseManagerPeer::LICENSE_DATA, LicenseManagerPeer::LICENSE_PATH, LicenseManagerPeer::LICENSE_WORKSPACE, LicenseManagerPeer::LICENSE_TYPE, ),
        BasePeer::TYPE_FIELDNAME => array ('LICENSE_UID', 'LICENSE_USER', 'LICENSE_START', 'LICENSE_END', 'LICENSE_SPAN', 'LICENSE_STATUS', 'LICENSE_DATA', 'LICENSE_PATH', 'LICENSE_WORKSPACE', 'LICENSE_TYPE', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('LicenseUid' => 0, 'LicenseUser' => 1, 'LicenseStart' => 2, 'LicenseEnd' => 3, 'LicenseSpan' => 4, 'LicenseStatus' => 5, 'LicenseData' => 6, 'LicensePath' => 7, 'LicenseWorkspace' => 8, 'LicenseType' => 9, ),
        BasePeer::TYPE_COLNAME => array (LicenseManagerPeer::LICENSE_UID => 0, LicenseManagerPeer::LICENSE_USER => 1, LicenseManagerPeer::LICENSE_START => 2, LicenseManagerPeer::LICENSE_END => 3, LicenseManagerPeer::LICENSE_SPAN => 4, LicenseManagerPeer::LICENSE_STATUS => 5, LicenseManagerPeer::LICENSE_DATA => 6, LicenseManagerPeer::LICENSE_PATH => 7, LicenseManagerPeer::LICENSE_WORKSPACE => 8, LicenseManagerPeer::LICENSE_TYPE => 9, ),
        BasePeer::TYPE_FIELDNAME => array ('LICENSE_UID' => 0, 'LICENSE_USER' => 1, 'LICENSE_START' => 2, 'LICENSE_END' => 3, 'LICENSE_SPAN' => 4, 'LICENSE_STATUS' => 5, 'LICENSE_DATA' => 6, 'LICENSE_PATH' => 7, 'LICENSE_WORKSPACE' => 8, 'LICENSE_TYPE' => 9, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/LicenseManagerMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.LicenseManagerMapBuilder');
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
            $map = LicenseManagerPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. LicenseManagerPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(LicenseManagerPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_UID);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_USER);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_START);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_END);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_SPAN);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_STATUS);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_DATA);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_PATH);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_WORKSPACE);

        $criteria->addSelectColumn(LicenseManagerPeer::LICENSE_TYPE);

    }

    const COUNT = 'COUNT(LICENSE_MANAGER.LICENSE_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT LICENSE_MANAGER.LICENSE_UID)';

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
            $criteria->addSelectColumn(LicenseManagerPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(LicenseManagerPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = LicenseManagerPeer::doSelectRS($criteria, $con);
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
     * @return     LicenseManager
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = LicenseManagerPeer::doSelect($critcopy, $con);
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
        return LicenseManagerPeer::populateObjects(LicenseManagerPeer::doSelectRS($criteria, $con));
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
            LicenseManagerPeer::addSelectColumns($criteria);
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
        $cls = LicenseManagerPeer::getOMClass();
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
        return LicenseManagerPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a LicenseManager or Criteria object.
     *
     * @param      mixed $values Criteria or LicenseManager object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from LicenseManager object
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
     * Method perform an UPDATE on the database, given a LicenseManager or Criteria object.
     *
     * @param      mixed $values Criteria or LicenseManager object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(LicenseManagerPeer::LICENSE_UID);
            $selectCriteria->add(LicenseManagerPeer::LICENSE_UID, $criteria->remove(LicenseManagerPeer::LICENSE_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the LICENSE_MANAGER table.
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
            $affectedRows += BasePeer::doDeleteAll(LicenseManagerPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a LicenseManager or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or LicenseManager object or primary key or array of primary keys
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
            $con = Propel::getConnection(LicenseManagerPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof LicenseManager) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(LicenseManagerPeer::LICENSE_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given LicenseManager object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      LicenseManager $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(LicenseManager $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(LicenseManagerPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(LicenseManagerPeer::TABLE_NAME);

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

        return BasePeer::doValidate(LicenseManagerPeer::DATABASE_NAME, LicenseManagerPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     LicenseManager
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(LicenseManagerPeer::DATABASE_NAME);

        $criteria->add(LicenseManagerPeer::LICENSE_UID, $pk);


        $v = LicenseManagerPeer::doSelect($criteria, $con);

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
            $criteria->add(LicenseManagerPeer::LICENSE_UID, $pks, Criteria::IN);
            $objs = LicenseManagerPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseLicenseManagerPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/LicenseManagerMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.LicenseManagerMapBuilder');
}

