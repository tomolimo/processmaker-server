<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by ListUnassignedPeer::getOMClass()
include_once 'classes/model/ListUnassigned.php';

/**
 * Base static class for performing query and update operations on the 'LIST_UNASSIGNED' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseListUnassignedPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'LIST_UNASSIGNED';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.ListUnassigned';

    /** The total number of columns. */
    const NUM_COLUMNS = 18;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the APP_UID field */
    const APP_UID = 'LIST_UNASSIGNED.APP_UID';

    /** the column name for the DEL_INDEX field */
    const DEL_INDEX = 'LIST_UNASSIGNED.DEL_INDEX';

    /** the column name for the TAS_UID field */
    const TAS_UID = 'LIST_UNASSIGNED.TAS_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'LIST_UNASSIGNED.PRO_UID';

    /** the column name for the APP_NUMBER field */
    const APP_NUMBER = 'LIST_UNASSIGNED.APP_NUMBER';

    /** the column name for the APP_TITLE field */
    const APP_TITLE = 'LIST_UNASSIGNED.APP_TITLE';

    /** the column name for the APP_PRO_TITLE field */
    const APP_PRO_TITLE = 'LIST_UNASSIGNED.APP_PRO_TITLE';

    /** the column name for the APP_TAS_TITLE field */
    const APP_TAS_TITLE = 'LIST_UNASSIGNED.APP_TAS_TITLE';

    /** the column name for the DEL_PREVIOUS_USR_USERNAME field */
    const DEL_PREVIOUS_USR_USERNAME = 'LIST_UNASSIGNED.DEL_PREVIOUS_USR_USERNAME';

    /** the column name for the DEL_PREVIOUS_USR_FIRSTNAME field */
    const DEL_PREVIOUS_USR_FIRSTNAME = 'LIST_UNASSIGNED.DEL_PREVIOUS_USR_FIRSTNAME';

    /** the column name for the DEL_PREVIOUS_USR_LASTNAME field */
    const DEL_PREVIOUS_USR_LASTNAME = 'LIST_UNASSIGNED.DEL_PREVIOUS_USR_LASTNAME';

    /** the column name for the APP_UPDATE_DATE field */
    const APP_UPDATE_DATE = 'LIST_UNASSIGNED.APP_UPDATE_DATE';

    /** the column name for the DEL_PREVIOUS_USR_UID field */
    const DEL_PREVIOUS_USR_UID = 'LIST_UNASSIGNED.DEL_PREVIOUS_USR_UID';

    /** the column name for the DEL_DELEGATE_DATE field */
    const DEL_DELEGATE_DATE = 'LIST_UNASSIGNED.DEL_DELEGATE_DATE';

    /** the column name for the DEL_DUE_DATE field */
    const DEL_DUE_DATE = 'LIST_UNASSIGNED.DEL_DUE_DATE';

    /** the column name for the DEL_PRIORITY field */
    const DEL_PRIORITY = 'LIST_UNASSIGNED.DEL_PRIORITY';

    /** the column name for the PRO_ID field */
    const PRO_ID = 'LIST_UNASSIGNED.PRO_ID';

    /** the column name for the TAS_ID field */
    const TAS_ID = 'LIST_UNASSIGNED.TAS_ID';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid', 'DelIndex', 'TasUid', 'ProUid', 'AppNumber', 'AppTitle', 'AppProTitle', 'AppTasTitle', 'DelPreviousUsrUsername', 'DelPreviousUsrFirstname', 'DelPreviousUsrLastname', 'AppUpdateDate', 'DelPreviousUsrUid', 'DelDelegateDate', 'DelDueDate', 'DelPriority', 'ProId', 'TasId', ),
        BasePeer::TYPE_COLNAME => array (ListUnassignedPeer::APP_UID, ListUnassignedPeer::DEL_INDEX, ListUnassignedPeer::TAS_UID, ListUnassignedPeer::PRO_UID, ListUnassignedPeer::APP_NUMBER, ListUnassignedPeer::APP_TITLE, ListUnassignedPeer::APP_PRO_TITLE, ListUnassignedPeer::APP_TAS_TITLE, ListUnassignedPeer::DEL_PREVIOUS_USR_USERNAME, ListUnassignedPeer::DEL_PREVIOUS_USR_FIRSTNAME, ListUnassignedPeer::DEL_PREVIOUS_USR_LASTNAME, ListUnassignedPeer::APP_UPDATE_DATE, ListUnassignedPeer::DEL_PREVIOUS_USR_UID, ListUnassignedPeer::DEL_DELEGATE_DATE, ListUnassignedPeer::DEL_DUE_DATE, ListUnassignedPeer::DEL_PRIORITY, ListUnassignedPeer::PRO_ID, ListUnassignedPeer::TAS_ID, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID', 'DEL_INDEX', 'TAS_UID', 'PRO_UID', 'APP_NUMBER', 'APP_TITLE', 'APP_PRO_TITLE', 'APP_TAS_TITLE', 'DEL_PREVIOUS_USR_USERNAME', 'DEL_PREVIOUS_USR_FIRSTNAME', 'DEL_PREVIOUS_USR_LASTNAME', 'APP_UPDATE_DATE', 'DEL_PREVIOUS_USR_UID', 'DEL_DELEGATE_DATE', 'DEL_DUE_DATE', 'DEL_PRIORITY', 'PRO_ID', 'TAS_ID', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid' => 0, 'DelIndex' => 1, 'TasUid' => 2, 'ProUid' => 3, 'AppNumber' => 4, 'AppTitle' => 5, 'AppProTitle' => 6, 'AppTasTitle' => 7, 'DelPreviousUsrUsername' => 8, 'DelPreviousUsrFirstname' => 9, 'DelPreviousUsrLastname' => 10, 'AppUpdateDate' => 11, 'DelPreviousUsrUid' => 12, 'DelDelegateDate' => 13, 'DelDueDate' => 14, 'DelPriority' => 15, 'ProId' => 16, 'TasId' => 17, ),
        BasePeer::TYPE_COLNAME => array (ListUnassignedPeer::APP_UID => 0, ListUnassignedPeer::DEL_INDEX => 1, ListUnassignedPeer::TAS_UID => 2, ListUnassignedPeer::PRO_UID => 3, ListUnassignedPeer::APP_NUMBER => 4, ListUnassignedPeer::APP_TITLE => 5, ListUnassignedPeer::APP_PRO_TITLE => 6, ListUnassignedPeer::APP_TAS_TITLE => 7, ListUnassignedPeer::DEL_PREVIOUS_USR_USERNAME => 8, ListUnassignedPeer::DEL_PREVIOUS_USR_FIRSTNAME => 9, ListUnassignedPeer::DEL_PREVIOUS_USR_LASTNAME => 10, ListUnassignedPeer::APP_UPDATE_DATE => 11, ListUnassignedPeer::DEL_PREVIOUS_USR_UID => 12, ListUnassignedPeer::DEL_DELEGATE_DATE => 13, ListUnassignedPeer::DEL_DUE_DATE => 14, ListUnassignedPeer::DEL_PRIORITY => 15, ListUnassignedPeer::PRO_ID => 16, ListUnassignedPeer::TAS_ID => 17, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID' => 0, 'DEL_INDEX' => 1, 'TAS_UID' => 2, 'PRO_UID' => 3, 'APP_NUMBER' => 4, 'APP_TITLE' => 5, 'APP_PRO_TITLE' => 6, 'APP_TAS_TITLE' => 7, 'DEL_PREVIOUS_USR_USERNAME' => 8, 'DEL_PREVIOUS_USR_FIRSTNAME' => 9, 'DEL_PREVIOUS_USR_LASTNAME' => 10, 'APP_UPDATE_DATE' => 11, 'DEL_PREVIOUS_USR_UID' => 12, 'DEL_DELEGATE_DATE' => 13, 'DEL_DUE_DATE' => 14, 'DEL_PRIORITY' => 15, 'PRO_ID' => 16, 'TAS_ID' => 17, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/ListUnassignedMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.ListUnassignedMapBuilder');
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
            $map = ListUnassignedPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. ListUnassignedPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ListUnassignedPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(ListUnassignedPeer::APP_UID);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_INDEX);

        $criteria->addSelectColumn(ListUnassignedPeer::TAS_UID);

        $criteria->addSelectColumn(ListUnassignedPeer::PRO_UID);

        $criteria->addSelectColumn(ListUnassignedPeer::APP_NUMBER);

        $criteria->addSelectColumn(ListUnassignedPeer::APP_TITLE);

        $criteria->addSelectColumn(ListUnassignedPeer::APP_PRO_TITLE);

        $criteria->addSelectColumn(ListUnassignedPeer::APP_TAS_TITLE);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_PREVIOUS_USR_USERNAME);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_PREVIOUS_USR_FIRSTNAME);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_PREVIOUS_USR_LASTNAME);

        $criteria->addSelectColumn(ListUnassignedPeer::APP_UPDATE_DATE);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_PREVIOUS_USR_UID);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_DELEGATE_DATE);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_DUE_DATE);

        $criteria->addSelectColumn(ListUnassignedPeer::DEL_PRIORITY);

        $criteria->addSelectColumn(ListUnassignedPeer::PRO_ID);

        $criteria->addSelectColumn(ListUnassignedPeer::TAS_ID);

    }

    const COUNT = 'COUNT(LIST_UNASSIGNED.APP_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT LIST_UNASSIGNED.APP_UID)';

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
            $criteria->addSelectColumn(ListUnassignedPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(ListUnassignedPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = ListUnassignedPeer::doSelectRS($criteria, $con);
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
     * @return     ListUnassigned
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ListUnassignedPeer::doSelect($critcopy, $con);
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
        return ListUnassignedPeer::populateObjects(ListUnassignedPeer::doSelectRS($criteria, $con));
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
            ListUnassignedPeer::addSelectColumns($criteria);
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
        $cls = ListUnassignedPeer::getOMClass();
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
        return ListUnassignedPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a ListUnassigned or Criteria object.
     *
     * @param      mixed $values Criteria or ListUnassigned object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from ListUnassigned object
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
     * Method perform an UPDATE on the database, given a ListUnassigned or Criteria object.
     *
     * @param      mixed $values Criteria or ListUnassigned object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(ListUnassignedPeer::APP_UID);
            $selectCriteria->add(ListUnassignedPeer::APP_UID, $criteria->remove(ListUnassignedPeer::APP_UID), $comparison);

            $comparison = $criteria->getComparison(ListUnassignedPeer::DEL_INDEX);
            $selectCriteria->add(ListUnassignedPeer::DEL_INDEX, $criteria->remove(ListUnassignedPeer::DEL_INDEX), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the LIST_UNASSIGNED table.
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
            $affectedRows += BasePeer::doDeleteAll(ListUnassignedPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a ListUnassigned or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ListUnassigned object or primary key or array of primary keys
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
            $con = Propel::getConnection(ListUnassignedPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof ListUnassigned) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey
            // values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            $vals = array();
            foreach ($values as $value) {

                $vals[0][] = $value[0];
                $vals[1][] = $value[1];
            }

            $criteria->add(ListUnassignedPeer::APP_UID, $vals[0], Criteria::IN);
            $criteria->add(ListUnassignedPeer::DEL_INDEX, $vals[1], Criteria::IN);
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
     * Validates all modified columns of given ListUnassigned object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      ListUnassigned $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(ListUnassigned $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ListUnassignedPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ListUnassignedPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ListUnassignedPeer::DATABASE_NAME, ListUnassignedPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param string $app_uid
       * @param int $del_index
        * @param      Connection $con
     * @return     ListUnassigned
     */
    public static function retrieveByPK($app_uid, $del_index, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }
        $criteria = new Criteria();
        $criteria->add(ListUnassignedPeer::APP_UID, $app_uid);
        $criteria->add(ListUnassignedPeer::DEL_INDEX, $del_index);
        $v = ListUnassignedPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseListUnassignedPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/ListUnassignedMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.ListUnassignedMapBuilder');
}

