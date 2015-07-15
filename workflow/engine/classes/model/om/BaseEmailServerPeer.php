<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by EmailServerPeer::getOMClass()
include_once 'classes/model/EmailServer.php';

/**
 * Base static class for performing query and update operations on the 'EMAIL_SERVER' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseEmailServerPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'EMAIL_SERVER';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.EmailServer';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the MESS_UID field */
    const MESS_UID = 'EMAIL_SERVER.MESS_UID';

    /** the column name for the MESS_ENGINE field */
    const MESS_ENGINE = 'EMAIL_SERVER.MESS_ENGINE';

    /** the column name for the MESS_SERVER field */
    const MESS_SERVER = 'EMAIL_SERVER.MESS_SERVER';

    /** the column name for the MESS_PORT field */
    const MESS_PORT = 'EMAIL_SERVER.MESS_PORT';

    /** the column name for the MESS_RAUTH field */
    const MESS_RAUTH = 'EMAIL_SERVER.MESS_RAUTH';

    /** the column name for the MESS_ACCOUNT field */
    const MESS_ACCOUNT = 'EMAIL_SERVER.MESS_ACCOUNT';

    /** the column name for the MESS_PASSWORD field */
    const MESS_PASSWORD = 'EMAIL_SERVER.MESS_PASSWORD';

    /** the column name for the MESS_FROM_MAIL field */
    const MESS_FROM_MAIL = 'EMAIL_SERVER.MESS_FROM_MAIL';

    /** the column name for the MESS_FROM_NAME field */
    const MESS_FROM_NAME = 'EMAIL_SERVER.MESS_FROM_NAME';

    /** the column name for the SMTPSECURE field */
    const SMTPSECURE = 'EMAIL_SERVER.SMTPSECURE';

    /** the column name for the MESS_TRY_SEND_INMEDIATLY field */
    const MESS_TRY_SEND_INMEDIATLY = 'EMAIL_SERVER.MESS_TRY_SEND_INMEDIATLY';

    /** the column name for the MAIL_TO field */
    const MAIL_TO = 'EMAIL_SERVER.MAIL_TO';

    /** the column name for the MESS_DEFAULT field */
    const MESS_DEFAULT = 'EMAIL_SERVER.MESS_DEFAULT';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('MessUid', 'MessEngine', 'MessServer', 'MessPort', 'MessRauth', 'MessAccount', 'MessPassword', 'MessFromMail', 'MessFromName', 'Smtpsecure', 'MessTrySendInmediatly', 'MailTo', 'MessDefault', ),
        BasePeer::TYPE_COLNAME => array (EmailServerPeer::MESS_UID, EmailServerPeer::MESS_ENGINE, EmailServerPeer::MESS_SERVER, EmailServerPeer::MESS_PORT, EmailServerPeer::MESS_RAUTH, EmailServerPeer::MESS_ACCOUNT, EmailServerPeer::MESS_PASSWORD, EmailServerPeer::MESS_FROM_MAIL, EmailServerPeer::MESS_FROM_NAME, EmailServerPeer::SMTPSECURE, EmailServerPeer::MESS_TRY_SEND_INMEDIATLY, EmailServerPeer::MAIL_TO, EmailServerPeer::MESS_DEFAULT, ),
        BasePeer::TYPE_FIELDNAME => array ('MESS_UID', 'MESS_ENGINE', 'MESS_SERVER', 'MESS_PORT', 'MESS_RAUTH', 'MESS_ACCOUNT', 'MESS_PASSWORD', 'MESS_FROM_MAIL', 'MESS_FROM_NAME', 'SMTPSECURE', 'MESS_TRY_SEND_INMEDIATLY', 'MAIL_TO', 'MESS_DEFAULT', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('MessUid' => 0, 'MessEngine' => 1, 'MessServer' => 2, 'MessPort' => 3, 'MessRauth' => 4, 'MessAccount' => 5, 'MessPassword' => 6, 'MessFromMail' => 7, 'MessFromName' => 8, 'Smtpsecure' => 9, 'MessTrySendInmediatly' => 10, 'MailTo' => 11, 'MessDefault' => 12, ),
        BasePeer::TYPE_COLNAME => array (EmailServerPeer::MESS_UID => 0, EmailServerPeer::MESS_ENGINE => 1, EmailServerPeer::MESS_SERVER => 2, EmailServerPeer::MESS_PORT => 3, EmailServerPeer::MESS_RAUTH => 4, EmailServerPeer::MESS_ACCOUNT => 5, EmailServerPeer::MESS_PASSWORD => 6, EmailServerPeer::MESS_FROM_MAIL => 7, EmailServerPeer::MESS_FROM_NAME => 8, EmailServerPeer::SMTPSECURE => 9, EmailServerPeer::MESS_TRY_SEND_INMEDIATLY => 10, EmailServerPeer::MAIL_TO => 11, EmailServerPeer::MESS_DEFAULT => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('MESS_UID' => 0, 'MESS_ENGINE' => 1, 'MESS_SERVER' => 2, 'MESS_PORT' => 3, 'MESS_RAUTH' => 4, 'MESS_ACCOUNT' => 5, 'MESS_PASSWORD' => 6, 'MESS_FROM_MAIL' => 7, 'MESS_FROM_NAME' => 8, 'SMTPSECURE' => 9, 'MESS_TRY_SEND_INMEDIATLY' => 10, 'MAIL_TO' => 11, 'MESS_DEFAULT' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/EmailServerMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.EmailServerMapBuilder');
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
            $map = EmailServerPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. EmailServerPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(EmailServerPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(EmailServerPeer::MESS_UID);

        $criteria->addSelectColumn(EmailServerPeer::MESS_ENGINE);

        $criteria->addSelectColumn(EmailServerPeer::MESS_SERVER);

        $criteria->addSelectColumn(EmailServerPeer::MESS_PORT);

        $criteria->addSelectColumn(EmailServerPeer::MESS_RAUTH);

        $criteria->addSelectColumn(EmailServerPeer::MESS_ACCOUNT);

        $criteria->addSelectColumn(EmailServerPeer::MESS_PASSWORD);

        $criteria->addSelectColumn(EmailServerPeer::MESS_FROM_MAIL);

        $criteria->addSelectColumn(EmailServerPeer::MESS_FROM_NAME);

        $criteria->addSelectColumn(EmailServerPeer::SMTPSECURE);

        $criteria->addSelectColumn(EmailServerPeer::MESS_TRY_SEND_INMEDIATLY);

        $criteria->addSelectColumn(EmailServerPeer::MAIL_TO);

        $criteria->addSelectColumn(EmailServerPeer::MESS_DEFAULT);

    }

    const COUNT = 'COUNT(EMAIL_SERVER.MESS_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT EMAIL_SERVER.MESS_UID)';

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
            $criteria->addSelectColumn(EmailServerPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(EmailServerPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = EmailServerPeer::doSelectRS($criteria, $con);
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
     * @return     EmailServer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = EmailServerPeer::doSelect($critcopy, $con);
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
        return EmailServerPeer::populateObjects(EmailServerPeer::doSelectRS($criteria, $con));
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
            EmailServerPeer::addSelectColumns($criteria);
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
        $cls = EmailServerPeer::getOMClass();
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
        return EmailServerPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a EmailServer or Criteria object.
     *
     * @param      mixed $values Criteria or EmailServer object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from EmailServer object
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
     * Method perform an UPDATE on the database, given a EmailServer or Criteria object.
     *
     * @param      mixed $values Criteria or EmailServer object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(EmailServerPeer::MESS_UID);
            $selectCriteria->add(EmailServerPeer::MESS_UID, $criteria->remove(EmailServerPeer::MESS_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the EMAIL_SERVER table.
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
            $affectedRows += BasePeer::doDeleteAll(EmailServerPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a EmailServer or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or EmailServer object or primary key or array of primary keys
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
            $con = Propel::getConnection(EmailServerPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof EmailServer) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(EmailServerPeer::MESS_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given EmailServer object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      EmailServer $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(EmailServer $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(EmailServerPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(EmailServerPeer::TABLE_NAME);

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

        return BasePeer::doValidate(EmailServerPeer::DATABASE_NAME, EmailServerPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     EmailServer
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(EmailServerPeer::DATABASE_NAME);

        $criteria->add(EmailServerPeer::MESS_UID, $pk);


        $v = EmailServerPeer::doSelect($criteria, $con);

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
            $criteria->add(EmailServerPeer::MESS_UID, $pks, Criteria::IN);
            $objs = EmailServerPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseEmailServerPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/EmailServerMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.EmailServerMapBuilder');
}

