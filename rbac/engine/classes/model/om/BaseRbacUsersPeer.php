<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by RbacUsersPeer::getOMClass()
//include_once 'classes/model/RbacUsers.php';

/**
 * Base static class for performing query and update operations on the 'RBAC_USERS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseRbacUsersPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'rbac';

    /** the table name for this class */
    const TABLE_NAME = 'RBAC_USERS';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.RbacUsers';

    /** The total number of columns. */
    const NUM_COLUMNS = 14;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the USR_UID field */
    const USR_UID = 'RBAC_USERS.USR_UID';

    /** the column name for the USR_USERNAME field */
    const USR_USERNAME = 'RBAC_USERS.USR_USERNAME';

    /** the column name for the USR_PASSWORD field */
    const USR_PASSWORD = 'RBAC_USERS.USR_PASSWORD';

    /** the column name for the USR_FIRSTNAME field */
    const USR_FIRSTNAME = 'RBAC_USERS.USR_FIRSTNAME';

    /** the column name for the USR_LASTNAME field */
    const USR_LASTNAME = 'RBAC_USERS.USR_LASTNAME';

    /** the column name for the USR_EMAIL field */
    const USR_EMAIL = 'RBAC_USERS.USR_EMAIL';

    /** the column name for the USR_DUE_DATE field */
    const USR_DUE_DATE = 'RBAC_USERS.USR_DUE_DATE';

    /** the column name for the USR_CREATE_DATE field */
    const USR_CREATE_DATE = 'RBAC_USERS.USR_CREATE_DATE';

    /** the column name for the USR_UPDATE_DATE field */
    const USR_UPDATE_DATE = 'RBAC_USERS.USR_UPDATE_DATE';

    /** the column name for the USR_STATUS field */
    const USR_STATUS = 'RBAC_USERS.USR_STATUS';

    /** the column name for the USR_AUTH_TYPE field */
    const USR_AUTH_TYPE = 'RBAC_USERS.USR_AUTH_TYPE';

    /** the column name for the UID_AUTH_SOURCE field */
    const UID_AUTH_SOURCE = 'RBAC_USERS.UID_AUTH_SOURCE';

    /** the column name for the USR_AUTH_USER_DN field */
    const USR_AUTH_USER_DN = 'RBAC_USERS.USR_AUTH_USER_DN';

    /** the column name for the USR_AUTH_SUPERVISOR_DN field */
    const USR_AUTH_SUPERVISOR_DN = 'RBAC_USERS.USR_AUTH_SUPERVISOR_DN';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('UsrUid', 'UsrUsername', 'UsrPassword', 'UsrFirstname', 'UsrLastname', 'UsrEmail', 'UsrDueDate', 'UsrCreateDate', 'UsrUpdateDate', 'UsrStatus', 'UsrAuthType', 'UidAuthSource', 'UsrAuthUserDn', 'UsrAuthSupervisorDn', ),
        BasePeer::TYPE_COLNAME => array (RbacUsersPeer::USR_UID, RbacUsersPeer::USR_USERNAME, RbacUsersPeer::USR_PASSWORD, RbacUsersPeer::USR_FIRSTNAME, RbacUsersPeer::USR_LASTNAME, RbacUsersPeer::USR_EMAIL, RbacUsersPeer::USR_DUE_DATE, RbacUsersPeer::USR_CREATE_DATE, RbacUsersPeer::USR_UPDATE_DATE, RbacUsersPeer::USR_STATUS, RbacUsersPeer::USR_AUTH_TYPE, RbacUsersPeer::UID_AUTH_SOURCE, RbacUsersPeer::USR_AUTH_USER_DN, RbacUsersPeer::USR_AUTH_SUPERVISOR_DN, ),
        BasePeer::TYPE_FIELDNAME => array ('USR_UID', 'USR_USERNAME', 'USR_PASSWORD', 'USR_FIRSTNAME', 'USR_LASTNAME', 'USR_EMAIL', 'USR_DUE_DATE', 'USR_CREATE_DATE', 'USR_UPDATE_DATE', 'USR_STATUS', 'USR_AUTH_TYPE', 'UID_AUTH_SOURCE', 'USR_AUTH_USER_DN', 'USR_AUTH_SUPERVISOR_DN', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('UsrUid' => 0, 'UsrUsername' => 1, 'UsrPassword' => 2, 'UsrFirstname' => 3, 'UsrLastname' => 4, 'UsrEmail' => 5, 'UsrDueDate' => 6, 'UsrCreateDate' => 7, 'UsrUpdateDate' => 8, 'UsrStatus' => 9, 'UsrAuthType' => 10, 'UidAuthSource' => 11, 'UsrAuthUserDn' => 12, 'UsrAuthSupervisorDn' => 13, ),
        BasePeer::TYPE_COLNAME => array (RbacUsersPeer::USR_UID => 0, RbacUsersPeer::USR_USERNAME => 1, RbacUsersPeer::USR_PASSWORD => 2, RbacUsersPeer::USR_FIRSTNAME => 3, RbacUsersPeer::USR_LASTNAME => 4, RbacUsersPeer::USR_EMAIL => 5, RbacUsersPeer::USR_DUE_DATE => 6, RbacUsersPeer::USR_CREATE_DATE => 7, RbacUsersPeer::USR_UPDATE_DATE => 8, RbacUsersPeer::USR_STATUS => 9, RbacUsersPeer::USR_AUTH_TYPE => 10, RbacUsersPeer::UID_AUTH_SOURCE => 11, RbacUsersPeer::USR_AUTH_USER_DN => 12, RbacUsersPeer::USR_AUTH_SUPERVISOR_DN => 13, ),
        BasePeer::TYPE_FIELDNAME => array ('USR_UID' => 0, 'USR_USERNAME' => 1, 'USR_PASSWORD' => 2, 'USR_FIRSTNAME' => 3, 'USR_LASTNAME' => 4, 'USR_EMAIL' => 5, 'USR_DUE_DATE' => 6, 'USR_CREATE_DATE' => 7, 'USR_UPDATE_DATE' => 8, 'USR_STATUS' => 9, 'USR_AUTH_TYPE' => 10, 'UID_AUTH_SOURCE' => 11, 'USR_AUTH_USER_DN' => 12, 'USR_AUTH_SUPERVISOR_DN' => 13, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/RbacUsersMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.RbacUsersMapBuilder');
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
            $map = RbacUsersPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. RbacUsersPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(RbacUsersPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(RbacUsersPeer::USR_UID);

        $criteria->addSelectColumn(RbacUsersPeer::USR_USERNAME);

        $criteria->addSelectColumn(RbacUsersPeer::USR_PASSWORD);

        $criteria->addSelectColumn(RbacUsersPeer::USR_FIRSTNAME);

        $criteria->addSelectColumn(RbacUsersPeer::USR_LASTNAME);

        $criteria->addSelectColumn(RbacUsersPeer::USR_EMAIL);

        $criteria->addSelectColumn(RbacUsersPeer::USR_DUE_DATE);

        $criteria->addSelectColumn(RbacUsersPeer::USR_CREATE_DATE);

        $criteria->addSelectColumn(RbacUsersPeer::USR_UPDATE_DATE);

        $criteria->addSelectColumn(RbacUsersPeer::USR_STATUS);

        $criteria->addSelectColumn(RbacUsersPeer::USR_AUTH_TYPE);

        $criteria->addSelectColumn(RbacUsersPeer::UID_AUTH_SOURCE);

        $criteria->addSelectColumn(RbacUsersPeer::USR_AUTH_USER_DN);

        $criteria->addSelectColumn(RbacUsersPeer::USR_AUTH_SUPERVISOR_DN);

    }

    const COUNT = 'COUNT(RBAC_USERS.USR_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT RBAC_USERS.USR_UID)';

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
            $criteria->addSelectColumn(RbacUsersPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(RbacUsersPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = RbacUsersPeer::doSelectRS($criteria, $con);
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
     * @return     RbacUsers
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = RbacUsersPeer::doSelect($critcopy, $con);
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
        return RbacUsersPeer::populateObjects(RbacUsersPeer::doSelectRS($criteria, $con));
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
            RbacUsersPeer::addSelectColumns($criteria);
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
        $cls = RbacUsersPeer::getOMClass();
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
        return RbacUsersPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a RbacUsers or Criteria object.
     *
     * @param      mixed $values Criteria or RbacUsers object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from RbacUsers object
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
     * Method perform an UPDATE on the database, given a RbacUsers or Criteria object.
     *
     * @param      mixed $values Criteria or RbacUsers object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(RbacUsersPeer::USR_UID);
            $selectCriteria->add(RbacUsersPeer::USR_UID, $criteria->remove(RbacUsersPeer::USR_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the RBAC_USERS table.
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
            $affectedRows += BasePeer::doDeleteAll(RbacUsersPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a RbacUsers or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or RbacUsers object or primary key or array of primary keys
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
            $con = Propel::getConnection(RbacUsersPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof RbacUsers) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(RbacUsersPeer::USR_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given RbacUsers object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      RbacUsers $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(RbacUsers $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(RbacUsersPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(RbacUsersPeer::TABLE_NAME);

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

        return BasePeer::doValidate(RbacUsersPeer::DATABASE_NAME, RbacUsersPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     RbacUsers
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(RbacUsersPeer::DATABASE_NAME);

        $criteria->add(RbacUsersPeer::USR_UID, $pk);


        $v = RbacUsersPeer::doSelect($criteria, $con);

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
            $criteria->add(RbacUsersPeer::USR_UID, $pks, Criteria::IN);
            $objs = RbacUsersPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseRbacUsersPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/RbacUsersMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.RbacUsersMapBuilder');
}

