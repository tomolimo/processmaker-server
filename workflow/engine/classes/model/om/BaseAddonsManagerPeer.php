<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by AddonsManagerPeer::getOMClass()
include_once 'classes/model/AddonsManager.php';

/**
 * Base static class for performing query and update operations on the 'ADDONS_MANAGER' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAddonsManagerPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'ADDONS_MANAGER';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.AddonsManager';

    /** The total number of columns. */
    const NUM_COLUMNS = 18;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the ADDON_ID field */
    const ADDON_ID = 'ADDONS_MANAGER.ADDON_ID';

    /** the column name for the STORE_ID field */
    const STORE_ID = 'ADDONS_MANAGER.STORE_ID';

    /** the column name for the ADDON_NAME field */
    const ADDON_NAME = 'ADDONS_MANAGER.ADDON_NAME';

    /** the column name for the ADDON_NICK field */
    const ADDON_NICK = 'ADDONS_MANAGER.ADDON_NICK';

    /** the column name for the ADDON_DOWNLOAD_FILENAME field */
    const ADDON_DOWNLOAD_FILENAME = 'ADDONS_MANAGER.ADDON_DOWNLOAD_FILENAME';

    /** the column name for the ADDON_DESCRIPTION field */
    const ADDON_DESCRIPTION = 'ADDONS_MANAGER.ADDON_DESCRIPTION';

    /** the column name for the ADDON_STATE field */
    const ADDON_STATE = 'ADDONS_MANAGER.ADDON_STATE';

    /** the column name for the ADDON_STATE_CHANGED field */
    const ADDON_STATE_CHANGED = 'ADDONS_MANAGER.ADDON_STATE_CHANGED';

    /** the column name for the ADDON_STATUS field */
    const ADDON_STATUS = 'ADDONS_MANAGER.ADDON_STATUS';

    /** the column name for the ADDON_VERSION field */
    const ADDON_VERSION = 'ADDONS_MANAGER.ADDON_VERSION';

    /** the column name for the ADDON_TYPE field */
    const ADDON_TYPE = 'ADDONS_MANAGER.ADDON_TYPE';

    /** the column name for the ADDON_PUBLISHER field */
    const ADDON_PUBLISHER = 'ADDONS_MANAGER.ADDON_PUBLISHER';

    /** the column name for the ADDON_RELEASE_DATE field */
    const ADDON_RELEASE_DATE = 'ADDONS_MANAGER.ADDON_RELEASE_DATE';

    /** the column name for the ADDON_RELEASE_TYPE field */
    const ADDON_RELEASE_TYPE = 'ADDONS_MANAGER.ADDON_RELEASE_TYPE';

    /** the column name for the ADDON_RELEASE_NOTES field */
    const ADDON_RELEASE_NOTES = 'ADDONS_MANAGER.ADDON_RELEASE_NOTES';

    /** the column name for the ADDON_DOWNLOAD_URL field */
    const ADDON_DOWNLOAD_URL = 'ADDONS_MANAGER.ADDON_DOWNLOAD_URL';

    /** the column name for the ADDON_DOWNLOAD_PROGRESS field */
    const ADDON_DOWNLOAD_PROGRESS = 'ADDONS_MANAGER.ADDON_DOWNLOAD_PROGRESS';

    /** the column name for the ADDON_DOWNLOAD_MD5 field */
    const ADDON_DOWNLOAD_MD5 = 'ADDONS_MANAGER.ADDON_DOWNLOAD_MD5';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('AddonId', 'StoreId', 'AddonName', 'AddonNick', 'AddonDownloadFilename', 'AddonDescription', 'AddonState', 'AddonStateChanged', 'AddonStatus', 'AddonVersion', 'AddonType', 'AddonPublisher', 'AddonReleaseDate', 'AddonReleaseType', 'AddonReleaseNotes', 'AddonDownloadUrl', 'AddonDownloadProgress', 'AddonDownloadMd5', ),
        BasePeer::TYPE_COLNAME => array (AddonsManagerPeer::ADDON_ID, AddonsManagerPeer::STORE_ID, AddonsManagerPeer::ADDON_NAME, AddonsManagerPeer::ADDON_NICK, AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME, AddonsManagerPeer::ADDON_DESCRIPTION, AddonsManagerPeer::ADDON_STATE, AddonsManagerPeer::ADDON_STATE_CHANGED, AddonsManagerPeer::ADDON_STATUS, AddonsManagerPeer::ADDON_VERSION, AddonsManagerPeer::ADDON_TYPE, AddonsManagerPeer::ADDON_PUBLISHER, AddonsManagerPeer::ADDON_RELEASE_DATE, AddonsManagerPeer::ADDON_RELEASE_TYPE, AddonsManagerPeer::ADDON_RELEASE_NOTES, AddonsManagerPeer::ADDON_DOWNLOAD_URL, AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS, AddonsManagerPeer::ADDON_DOWNLOAD_MD5, ),
        BasePeer::TYPE_FIELDNAME => array ('ADDON_ID', 'STORE_ID', 'ADDON_NAME', 'ADDON_NICK', 'ADDON_DOWNLOAD_FILENAME', 'ADDON_DESCRIPTION', 'ADDON_STATE', 'ADDON_STATE_CHANGED', 'ADDON_STATUS', 'ADDON_VERSION', 'ADDON_TYPE', 'ADDON_PUBLISHER', 'ADDON_RELEASE_DATE', 'ADDON_RELEASE_TYPE', 'ADDON_RELEASE_NOTES', 'ADDON_DOWNLOAD_URL', 'ADDON_DOWNLOAD_PROGRESS', 'ADDON_DOWNLOAD_MD5', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('AddonId' => 0, 'StoreId' => 1, 'AddonName' => 2, 'AddonNick' => 3, 'AddonDownloadFilename' => 4, 'AddonDescription' => 5, 'AddonState' => 6, 'AddonStateChanged' => 7, 'AddonStatus' => 8, 'AddonVersion' => 9, 'AddonType' => 10, 'AddonPublisher' => 11, 'AddonReleaseDate' => 12, 'AddonReleaseType' => 13, 'AddonReleaseNotes' => 14, 'AddonDownloadUrl' => 15, 'AddonDownloadProgress' => 16, 'AddonDownloadMd5' => 17, ),
        BasePeer::TYPE_COLNAME => array (AddonsManagerPeer::ADDON_ID => 0, AddonsManagerPeer::STORE_ID => 1, AddonsManagerPeer::ADDON_NAME => 2, AddonsManagerPeer::ADDON_NICK => 3, AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME => 4, AddonsManagerPeer::ADDON_DESCRIPTION => 5, AddonsManagerPeer::ADDON_STATE => 6, AddonsManagerPeer::ADDON_STATE_CHANGED => 7, AddonsManagerPeer::ADDON_STATUS => 8, AddonsManagerPeer::ADDON_VERSION => 9, AddonsManagerPeer::ADDON_TYPE => 10, AddonsManagerPeer::ADDON_PUBLISHER => 11, AddonsManagerPeer::ADDON_RELEASE_DATE => 12, AddonsManagerPeer::ADDON_RELEASE_TYPE => 13, AddonsManagerPeer::ADDON_RELEASE_NOTES => 14, AddonsManagerPeer::ADDON_DOWNLOAD_URL => 15, AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS => 16, AddonsManagerPeer::ADDON_DOWNLOAD_MD5 => 17, ),
        BasePeer::TYPE_FIELDNAME => array ('ADDON_ID' => 0, 'STORE_ID' => 1, 'ADDON_NAME' => 2, 'ADDON_NICK' => 3, 'ADDON_DOWNLOAD_FILENAME' => 4, 'ADDON_DESCRIPTION' => 5, 'ADDON_STATE' => 6, 'ADDON_STATE_CHANGED' => 7, 'ADDON_STATUS' => 8, 'ADDON_VERSION' => 9, 'ADDON_TYPE' => 10, 'ADDON_PUBLISHER' => 11, 'ADDON_RELEASE_DATE' => 12, 'ADDON_RELEASE_TYPE' => 13, 'ADDON_RELEASE_NOTES' => 14, 'ADDON_DOWNLOAD_URL' => 15, 'ADDON_DOWNLOAD_PROGRESS' => 16, 'ADDON_DOWNLOAD_MD5' => 17, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/AddonsManagerMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.AddonsManagerMapBuilder');
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
            $map = AddonsManagerPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. AddonsManagerPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(AddonsManagerPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_ID);

        $criteria->addSelectColumn(AddonsManagerPeer::STORE_ID);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_NAME);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_NICK);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_DESCRIPTION);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_STATE);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_STATE_CHANGED);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_STATUS);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_VERSION);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_TYPE);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_PUBLISHER);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_RELEASE_DATE);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_RELEASE_TYPE);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_RELEASE_NOTES);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_DOWNLOAD_URL);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS);

        $criteria->addSelectColumn(AddonsManagerPeer::ADDON_DOWNLOAD_MD5);

    }

    const COUNT = 'COUNT(ADDONS_MANAGER.ADDON_ID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT ADDONS_MANAGER.ADDON_ID)';

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
            $criteria->addSelectColumn(AddonsManagerPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(AddonsManagerPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = AddonsManagerPeer::doSelectRS($criteria, $con);
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
     * @return     AddonsManager
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = AddonsManagerPeer::doSelect($critcopy, $con);
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
        return AddonsManagerPeer::populateObjects(AddonsManagerPeer::doSelectRS($criteria, $con));
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
            AddonsManagerPeer::addSelectColumns($criteria);
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
        $cls = AddonsManagerPeer::getOMClass();
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
        return AddonsManagerPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a AddonsManager or Criteria object.
     *
     * @param      mixed $values Criteria or AddonsManager object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from AddonsManager object
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
     * Method perform an UPDATE on the database, given a AddonsManager or Criteria object.
     *
     * @param      mixed $values Criteria or AddonsManager object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(AddonsManagerPeer::ADDON_ID);
            $selectCriteria->add(AddonsManagerPeer::ADDON_ID, $criteria->remove(AddonsManagerPeer::ADDON_ID), $comparison);

            $comparison = $criteria->getComparison(AddonsManagerPeer::STORE_ID);
            $selectCriteria->add(AddonsManagerPeer::STORE_ID, $criteria->remove(AddonsManagerPeer::STORE_ID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the ADDONS_MANAGER table.
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
            $affectedRows += BasePeer::doDeleteAll(AddonsManagerPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a AddonsManager or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or AddonsManager object or primary key or array of primary keys
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
            $con = Propel::getConnection(AddonsManagerPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof AddonsManager) {

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

            $criteria->add(AddonsManagerPeer::ADDON_ID, $vals[0], Criteria::IN);
            $criteria->add(AddonsManagerPeer::STORE_ID, $vals[1], Criteria::IN);
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
     * Validates all modified columns of given AddonsManager object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      AddonsManager $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(AddonsManager $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(AddonsManagerPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(AddonsManagerPeer::TABLE_NAME);

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

        return BasePeer::doValidate(AddonsManagerPeer::DATABASE_NAME, AddonsManagerPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param string $addon_id
       * @param string $store_id
        * @param      Connection $con
     * @return     AddonsManager
     */
    public static function retrieveByPK($addon_id, $store_id, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }
        $criteria = new Criteria();
        $criteria->add(AddonsManagerPeer::ADDON_ID, $addon_id);
        $criteria->add(AddonsManagerPeer::STORE_ID, $store_id);
        $v = AddonsManagerPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseAddonsManagerPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/AddonsManagerMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.AddonsManagerMapBuilder');
}

