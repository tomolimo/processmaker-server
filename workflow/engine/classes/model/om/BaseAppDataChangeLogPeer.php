<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by AppDataChangeLogPeer::getOMClass()
include_once 'classes/model/AppDataChangeLog.php';

/**
 * Base static class for performing query and update operations on the 'APP_DATA_CHANGE_LOG' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAppDataChangeLogPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'APP_DATA_CHANGE_LOG';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.AppDataChangeLog';

    /** The total number of columns. */
    const NUM_COLUMNS = 16;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the CHANGE_LOG_ID field */
    const CHANGE_LOG_ID = 'APP_DATA_CHANGE_LOG.CHANGE_LOG_ID';

    /** the column name for the DATE field */
    const DATE = 'APP_DATA_CHANGE_LOG.DATE';

    /** the column name for the APP_NUMBER field */
    const APP_NUMBER = 'APP_DATA_CHANGE_LOG.APP_NUMBER';

    /** the column name for the DEL_INDEX field */
    const DEL_INDEX = 'APP_DATA_CHANGE_LOG.DEL_INDEX';

    /** the column name for the PRO_ID field */
    const PRO_ID = 'APP_DATA_CHANGE_LOG.PRO_ID';

    /** the column name for the TAS_ID field */
    const TAS_ID = 'APP_DATA_CHANGE_LOG.TAS_ID';

    /** the column name for the USR_ID field */
    const USR_ID = 'APP_DATA_CHANGE_LOG.USR_ID';

    /** the column name for the OBJECT_TYPE field */
    const OBJECT_TYPE = 'APP_DATA_CHANGE_LOG.OBJECT_TYPE';

    /** the column name for the OBJECT_ID field */
    const OBJECT_ID = 'APP_DATA_CHANGE_LOG.OBJECT_ID';

    /** the column name for the OBJECT_UID field */
    const OBJECT_UID = 'APP_DATA_CHANGE_LOG.OBJECT_UID';

    /** the column name for the EXECUTED_AT field */
    const EXECUTED_AT = 'APP_DATA_CHANGE_LOG.EXECUTED_AT';

    /** the column name for the SOURCE_ID field */
    const SOURCE_ID = 'APP_DATA_CHANGE_LOG.SOURCE_ID';

    /** the column name for the DATA field */
    const DATA = 'APP_DATA_CHANGE_LOG.DATA';

    /** the column name for the SKIN field */
    const SKIN = 'APP_DATA_CHANGE_LOG.SKIN';

    /** the column name for the LANGUAGE field */
    const LANGUAGE = 'APP_DATA_CHANGE_LOG.LANGUAGE';

    /** the column name for the ROW_MIGRATION field */
    const ROW_MIGRATION = 'APP_DATA_CHANGE_LOG.ROW_MIGRATION';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('ChangeLogId', 'Date', 'AppNumber', 'DelIndex', 'ProId', 'TasId', 'UsrId', 'ObjectType', 'ObjectId', 'ObjectUid', 'ExecutedAt', 'SourceId', 'Data', 'Skin', 'Language', 'RowMigration', ),
        BasePeer::TYPE_COLNAME => array (AppDataChangeLogPeer::CHANGE_LOG_ID, AppDataChangeLogPeer::DATE, AppDataChangeLogPeer::APP_NUMBER, AppDataChangeLogPeer::DEL_INDEX, AppDataChangeLogPeer::PRO_ID, AppDataChangeLogPeer::TAS_ID, AppDataChangeLogPeer::USR_ID, AppDataChangeLogPeer::OBJECT_TYPE, AppDataChangeLogPeer::OBJECT_ID, AppDataChangeLogPeer::OBJECT_UID, AppDataChangeLogPeer::EXECUTED_AT, AppDataChangeLogPeer::SOURCE_ID, AppDataChangeLogPeer::DATA, AppDataChangeLogPeer::SKIN, AppDataChangeLogPeer::LANGUAGE, AppDataChangeLogPeer::ROW_MIGRATION, ),
        BasePeer::TYPE_FIELDNAME => array ('CHANGE_LOG_ID', 'DATE', 'APP_NUMBER', 'DEL_INDEX', 'PRO_ID', 'TAS_ID', 'USR_ID', 'OBJECT_TYPE', 'OBJECT_ID', 'OBJECT_UID', 'EXECUTED_AT', 'SOURCE_ID', 'DATA', 'SKIN', 'LANGUAGE', 'ROW_MIGRATION', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('ChangeLogId' => 0, 'Date' => 1, 'AppNumber' => 2, 'DelIndex' => 3, 'ProId' => 4, 'TasId' => 5, 'UsrId' => 6, 'ObjectType' => 7, 'ObjectId' => 8, 'ObjectUid' => 9, 'ExecutedAt' => 10, 'SourceId' => 11, 'Data' => 12, 'Skin' => 13, 'Language' => 14, 'RowMigration' => 15, ),
        BasePeer::TYPE_COLNAME => array (AppDataChangeLogPeer::CHANGE_LOG_ID => 0, AppDataChangeLogPeer::DATE => 1, AppDataChangeLogPeer::APP_NUMBER => 2, AppDataChangeLogPeer::DEL_INDEX => 3, AppDataChangeLogPeer::PRO_ID => 4, AppDataChangeLogPeer::TAS_ID => 5, AppDataChangeLogPeer::USR_ID => 6, AppDataChangeLogPeer::OBJECT_TYPE => 7, AppDataChangeLogPeer::OBJECT_ID => 8, AppDataChangeLogPeer::OBJECT_UID => 9, AppDataChangeLogPeer::EXECUTED_AT => 10, AppDataChangeLogPeer::SOURCE_ID => 11, AppDataChangeLogPeer::DATA => 12, AppDataChangeLogPeer::SKIN => 13, AppDataChangeLogPeer::LANGUAGE => 14, AppDataChangeLogPeer::ROW_MIGRATION => 15, ),
        BasePeer::TYPE_FIELDNAME => array ('CHANGE_LOG_ID' => 0, 'DATE' => 1, 'APP_NUMBER' => 2, 'DEL_INDEX' => 3, 'PRO_ID' => 4, 'TAS_ID' => 5, 'USR_ID' => 6, 'OBJECT_TYPE' => 7, 'OBJECT_ID' => 8, 'OBJECT_UID' => 9, 'EXECUTED_AT' => 10, 'SOURCE_ID' => 11, 'DATA' => 12, 'SKIN' => 13, 'LANGUAGE' => 14, 'ROW_MIGRATION' => 15, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/AppDataChangeLogMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.AppDataChangeLogMapBuilder');
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
            $map = AppDataChangeLogPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. AppDataChangeLogPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(AppDataChangeLogPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(AppDataChangeLogPeer::CHANGE_LOG_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::DATE);

        $criteria->addSelectColumn(AppDataChangeLogPeer::APP_NUMBER);

        $criteria->addSelectColumn(AppDataChangeLogPeer::DEL_INDEX);

        $criteria->addSelectColumn(AppDataChangeLogPeer::PRO_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::TAS_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::USR_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::OBJECT_TYPE);

        $criteria->addSelectColumn(AppDataChangeLogPeer::OBJECT_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::OBJECT_UID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::EXECUTED_AT);

        $criteria->addSelectColumn(AppDataChangeLogPeer::SOURCE_ID);

        $criteria->addSelectColumn(AppDataChangeLogPeer::DATA);

        $criteria->addSelectColumn(AppDataChangeLogPeer::SKIN);

        $criteria->addSelectColumn(AppDataChangeLogPeer::LANGUAGE);

        $criteria->addSelectColumn(AppDataChangeLogPeer::ROW_MIGRATION);

    }

    const COUNT = 'COUNT(APP_DATA_CHANGE_LOG.CHANGE_LOG_ID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT APP_DATA_CHANGE_LOG.CHANGE_LOG_ID)';

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
            $criteria->addSelectColumn(AppDataChangeLogPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(AppDataChangeLogPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = AppDataChangeLogPeer::doSelectRS($criteria, $con);
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
     * @return     AppDataChangeLog
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = AppDataChangeLogPeer::doSelect($critcopy, $con);
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
        return AppDataChangeLogPeer::populateObjects(AppDataChangeLogPeer::doSelectRS($criteria, $con));
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
            AppDataChangeLogPeer::addSelectColumns($criteria);
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
        $cls = AppDataChangeLogPeer::getOMClass();
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
        return AppDataChangeLogPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a AppDataChangeLog or Criteria object.
     *
     * @param      mixed $values Criteria or AppDataChangeLog object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from AppDataChangeLog object
        }

                //$criteria->remove(AppDataChangeLogPeer::CHANGE_LOG_ID); // remove pkey col since this table uses auto-increment
                

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
     * Method perform an UPDATE on the database, given a AppDataChangeLog or Criteria object.
     *
     * @param      mixed $values Criteria or AppDataChangeLog object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(AppDataChangeLogPeer::CHANGE_LOG_ID);
            $selectCriteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, $criteria->remove(AppDataChangeLogPeer::CHANGE_LOG_ID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the APP_DATA_CHANGE_LOG table.
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
            $affectedRows += BasePeer::doDeleteAll(AppDataChangeLogPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a AppDataChangeLog or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or AppDataChangeLog object or primary key or array of primary keys
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
            $con = Propel::getConnection(AppDataChangeLogPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof AppDataChangeLog) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given AppDataChangeLog object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      AppDataChangeLog $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(AppDataChangeLog $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(AppDataChangeLogPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(AppDataChangeLogPeer::TABLE_NAME);

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

        return BasePeer::doValidate(AppDataChangeLogPeer::DATABASE_NAME, AppDataChangeLogPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     AppDataChangeLog
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(AppDataChangeLogPeer::DATABASE_NAME);

        $criteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, $pk);


        $v = AppDataChangeLogPeer::doSelect($criteria, $con);

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
            $criteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, $pks, Criteria::IN);
            $objs = AppDataChangeLogPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseAppDataChangeLogPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/AppDataChangeLogMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.AppDataChangeLogMapBuilder');
}

