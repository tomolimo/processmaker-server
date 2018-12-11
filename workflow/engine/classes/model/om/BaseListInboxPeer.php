<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by ListInboxPeer::getOMClass()
include_once 'classes/model/ListInbox.php';

/**
 * Base static class for performing query and update operations on the 'LIST_INBOX' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseListInboxPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'LIST_INBOX';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.ListInbox';

    /** The total number of columns. */
    const NUM_COLUMNS = 24;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the APP_UID field */
    const APP_UID = 'LIST_INBOX.APP_UID';

    /** the column name for the DEL_INDEX field */
    const DEL_INDEX = 'LIST_INBOX.DEL_INDEX';

    /** the column name for the USR_UID field */
    const USR_UID = 'LIST_INBOX.USR_UID';

    /** the column name for the TAS_UID field */
    const TAS_UID = 'LIST_INBOX.TAS_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'LIST_INBOX.PRO_UID';

    /** the column name for the APP_NUMBER field */
    const APP_NUMBER = 'LIST_INBOX.APP_NUMBER';

    /** the column name for the APP_STATUS field */
    const APP_STATUS = 'LIST_INBOX.APP_STATUS';

    /** the column name for the APP_TITLE field */
    const APP_TITLE = 'LIST_INBOX.APP_TITLE';

    /** the column name for the APP_PRO_TITLE field */
    const APP_PRO_TITLE = 'LIST_INBOX.APP_PRO_TITLE';

    /** the column name for the APP_TAS_TITLE field */
    const APP_TAS_TITLE = 'LIST_INBOX.APP_TAS_TITLE';

    /** the column name for the APP_UPDATE_DATE field */
    const APP_UPDATE_DATE = 'LIST_INBOX.APP_UPDATE_DATE';

    /** the column name for the DEL_PREVIOUS_USR_UID field */
    const DEL_PREVIOUS_USR_UID = 'LIST_INBOX.DEL_PREVIOUS_USR_UID';

    /** the column name for the DEL_PREVIOUS_USR_USERNAME field */
    const DEL_PREVIOUS_USR_USERNAME = 'LIST_INBOX.DEL_PREVIOUS_USR_USERNAME';

    /** the column name for the DEL_PREVIOUS_USR_FIRSTNAME field */
    const DEL_PREVIOUS_USR_FIRSTNAME = 'LIST_INBOX.DEL_PREVIOUS_USR_FIRSTNAME';

    /** the column name for the DEL_PREVIOUS_USR_LASTNAME field */
    const DEL_PREVIOUS_USR_LASTNAME = 'LIST_INBOX.DEL_PREVIOUS_USR_LASTNAME';

    /** the column name for the DEL_DELEGATE_DATE field */
    const DEL_DELEGATE_DATE = 'LIST_INBOX.DEL_DELEGATE_DATE';

    /** the column name for the DEL_INIT_DATE field */
    const DEL_INIT_DATE = 'LIST_INBOX.DEL_INIT_DATE';

    /** the column name for the DEL_DUE_DATE field */
    const DEL_DUE_DATE = 'LIST_INBOX.DEL_DUE_DATE';

    /** the column name for the DEL_RISK_DATE field */
    const DEL_RISK_DATE = 'LIST_INBOX.DEL_RISK_DATE';

    /** the column name for the DEL_PRIORITY field */
    const DEL_PRIORITY = 'LIST_INBOX.DEL_PRIORITY';

    /** the column name for the PRO_ID field */
    const PRO_ID = 'LIST_INBOX.PRO_ID';

    /** the column name for the USR_ID field */
    const USR_ID = 'LIST_INBOX.USR_ID';

    /** the column name for the TAS_ID field */
    const TAS_ID = 'LIST_INBOX.TAS_ID';

    /** the column name for the APP_STATUS_ID field */
    const APP_STATUS_ID = 'LIST_INBOX.APP_STATUS_ID';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid', 'DelIndex', 'UsrUid', 'TasUid', 'ProUid', 'AppNumber', 'AppStatus', 'AppTitle', 'AppProTitle', 'AppTasTitle', 'AppUpdateDate', 'DelPreviousUsrUid', 'DelPreviousUsrUsername', 'DelPreviousUsrFirstname', 'DelPreviousUsrLastname', 'DelDelegateDate', 'DelInitDate', 'DelDueDate', 'DelRiskDate', 'DelPriority', 'ProId', 'UsrId', 'TasId', 'AppStatusId', ),
        BasePeer::TYPE_COLNAME => array (ListInboxPeer::APP_UID, ListInboxPeer::DEL_INDEX, ListInboxPeer::USR_UID, ListInboxPeer::TAS_UID, ListInboxPeer::PRO_UID, ListInboxPeer::APP_NUMBER, ListInboxPeer::APP_STATUS, ListInboxPeer::APP_TITLE, ListInboxPeer::APP_PRO_TITLE, ListInboxPeer::APP_TAS_TITLE, ListInboxPeer::APP_UPDATE_DATE, ListInboxPeer::DEL_PREVIOUS_USR_UID, ListInboxPeer::DEL_PREVIOUS_USR_USERNAME, ListInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME, ListInboxPeer::DEL_PREVIOUS_USR_LASTNAME, ListInboxPeer::DEL_DELEGATE_DATE, ListInboxPeer::DEL_INIT_DATE, ListInboxPeer::DEL_DUE_DATE, ListInboxPeer::DEL_RISK_DATE, ListInboxPeer::DEL_PRIORITY, ListInboxPeer::PRO_ID, ListInboxPeer::USR_ID, ListInboxPeer::TAS_ID, ListInboxPeer::APP_STATUS_ID, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID', 'DEL_INDEX', 'USR_UID', 'TAS_UID', 'PRO_UID', 'APP_NUMBER', 'APP_STATUS', 'APP_TITLE', 'APP_PRO_TITLE', 'APP_TAS_TITLE', 'APP_UPDATE_DATE', 'DEL_PREVIOUS_USR_UID', 'DEL_PREVIOUS_USR_USERNAME', 'DEL_PREVIOUS_USR_FIRSTNAME', 'DEL_PREVIOUS_USR_LASTNAME', 'DEL_DELEGATE_DATE', 'DEL_INIT_DATE', 'DEL_DUE_DATE', 'DEL_RISK_DATE', 'DEL_PRIORITY', 'PRO_ID', 'USR_ID', 'TAS_ID', 'APP_STATUS_ID', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid' => 0, 'DelIndex' => 1, 'UsrUid' => 2, 'TasUid' => 3, 'ProUid' => 4, 'AppNumber' => 5, 'AppStatus' => 6, 'AppTitle' => 7, 'AppProTitle' => 8, 'AppTasTitle' => 9, 'AppUpdateDate' => 10, 'DelPreviousUsrUid' => 11, 'DelPreviousUsrUsername' => 12, 'DelPreviousUsrFirstname' => 13, 'DelPreviousUsrLastname' => 14, 'DelDelegateDate' => 15, 'DelInitDate' => 16, 'DelDueDate' => 17, 'DelRiskDate' => 18, 'DelPriority' => 19, 'ProId' => 20, 'UsrId' => 21, 'TasId' => 22, 'AppStatusId' => 23, ),
        BasePeer::TYPE_COLNAME => array (ListInboxPeer::APP_UID => 0, ListInboxPeer::DEL_INDEX => 1, ListInboxPeer::USR_UID => 2, ListInboxPeer::TAS_UID => 3, ListInboxPeer::PRO_UID => 4, ListInboxPeer::APP_NUMBER => 5, ListInboxPeer::APP_STATUS => 6, ListInboxPeer::APP_TITLE => 7, ListInboxPeer::APP_PRO_TITLE => 8, ListInboxPeer::APP_TAS_TITLE => 9, ListInboxPeer::APP_UPDATE_DATE => 10, ListInboxPeer::DEL_PREVIOUS_USR_UID => 11, ListInboxPeer::DEL_PREVIOUS_USR_USERNAME => 12, ListInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME => 13, ListInboxPeer::DEL_PREVIOUS_USR_LASTNAME => 14, ListInboxPeer::DEL_DELEGATE_DATE => 15, ListInboxPeer::DEL_INIT_DATE => 16, ListInboxPeer::DEL_DUE_DATE => 17, ListInboxPeer::DEL_RISK_DATE => 18, ListInboxPeer::DEL_PRIORITY => 19, ListInboxPeer::PRO_ID => 20, ListInboxPeer::USR_ID => 21, ListInboxPeer::TAS_ID => 22, ListInboxPeer::APP_STATUS_ID => 23, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID' => 0, 'DEL_INDEX' => 1, 'USR_UID' => 2, 'TAS_UID' => 3, 'PRO_UID' => 4, 'APP_NUMBER' => 5, 'APP_STATUS' => 6, 'APP_TITLE' => 7, 'APP_PRO_TITLE' => 8, 'APP_TAS_TITLE' => 9, 'APP_UPDATE_DATE' => 10, 'DEL_PREVIOUS_USR_UID' => 11, 'DEL_PREVIOUS_USR_USERNAME' => 12, 'DEL_PREVIOUS_USR_FIRSTNAME' => 13, 'DEL_PREVIOUS_USR_LASTNAME' => 14, 'DEL_DELEGATE_DATE' => 15, 'DEL_INIT_DATE' => 16, 'DEL_DUE_DATE' => 17, 'DEL_RISK_DATE' => 18, 'DEL_PRIORITY' => 19, 'PRO_ID' => 20, 'USR_ID' => 21, 'TAS_ID' => 22, 'APP_STATUS_ID' => 23, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/ListInboxMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.ListInboxMapBuilder');
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
            $map = ListInboxPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. ListInboxPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ListInboxPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(ListInboxPeer::APP_UID);

        $criteria->addSelectColumn(ListInboxPeer::DEL_INDEX);

        $criteria->addSelectColumn(ListInboxPeer::USR_UID);

        $criteria->addSelectColumn(ListInboxPeer::TAS_UID);

        $criteria->addSelectColumn(ListInboxPeer::PRO_UID);

        $criteria->addSelectColumn(ListInboxPeer::APP_NUMBER);

        $criteria->addSelectColumn(ListInboxPeer::APP_STATUS);

        $criteria->addSelectColumn(ListInboxPeer::APP_TITLE);

        $criteria->addSelectColumn(ListInboxPeer::APP_PRO_TITLE);

        $criteria->addSelectColumn(ListInboxPeer::APP_TAS_TITLE);

        $criteria->addSelectColumn(ListInboxPeer::APP_UPDATE_DATE);

        $criteria->addSelectColumn(ListInboxPeer::DEL_PREVIOUS_USR_UID);

        $criteria->addSelectColumn(ListInboxPeer::DEL_PREVIOUS_USR_USERNAME);

        $criteria->addSelectColumn(ListInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME);

        $criteria->addSelectColumn(ListInboxPeer::DEL_PREVIOUS_USR_LASTNAME);

        $criteria->addSelectColumn(ListInboxPeer::DEL_DELEGATE_DATE);

        $criteria->addSelectColumn(ListInboxPeer::DEL_INIT_DATE);

        $criteria->addSelectColumn(ListInboxPeer::DEL_DUE_DATE);

        $criteria->addSelectColumn(ListInboxPeer::DEL_RISK_DATE);

        $criteria->addSelectColumn(ListInboxPeer::DEL_PRIORITY);

        $criteria->addSelectColumn(ListInboxPeer::PRO_ID);

        $criteria->addSelectColumn(ListInboxPeer::USR_ID);

        $criteria->addSelectColumn(ListInboxPeer::TAS_ID);

        $criteria->addSelectColumn(ListInboxPeer::APP_STATUS_ID);

    }

    const COUNT = 'COUNT(LIST_INBOX.APP_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT LIST_INBOX.APP_UID)';

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
            $criteria->addSelectColumn(ListInboxPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(ListInboxPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = ListInboxPeer::doSelectRS($criteria, $con);
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
     * @return     ListInbox
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ListInboxPeer::doSelect($critcopy, $con);
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
        return ListInboxPeer::populateObjects(ListInboxPeer::doSelectRS($criteria, $con));
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
            ListInboxPeer::addSelectColumns($criteria);
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
        $cls = ListInboxPeer::getOMClass();
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
        return ListInboxPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a ListInbox or Criteria object.
     *
     * @param      mixed $values Criteria or ListInbox object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from ListInbox object
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
     * Method perform an UPDATE on the database, given a ListInbox or Criteria object.
     *
     * @param      mixed $values Criteria or ListInbox object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(ListInboxPeer::APP_UID);
            $selectCriteria->add(ListInboxPeer::APP_UID, $criteria->remove(ListInboxPeer::APP_UID), $comparison);

            $comparison = $criteria->getComparison(ListInboxPeer::DEL_INDEX);
            $selectCriteria->add(ListInboxPeer::DEL_INDEX, $criteria->remove(ListInboxPeer::DEL_INDEX), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the LIST_INBOX table.
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
            $affectedRows += BasePeer::doDeleteAll(ListInboxPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a ListInbox or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ListInbox object or primary key or array of primary keys
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
            $con = Propel::getConnection(ListInboxPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof ListInbox) {

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

            $criteria->add(ListInboxPeer::APP_UID, $vals[0], Criteria::IN);
            $criteria->add(ListInboxPeer::DEL_INDEX, $vals[1], Criteria::IN);
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
     * Validates all modified columns of given ListInbox object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      ListInbox $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(ListInbox $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ListInboxPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ListInboxPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ListInboxPeer::DATABASE_NAME, ListInboxPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param string $app_uid
       * @param int $del_index
        * @param      Connection $con
     * @return     ListInbox
     */
    public static function retrieveByPK($app_uid, $del_index, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }
        $criteria = new Criteria();
        $criteria->add(ListInboxPeer::APP_UID, $app_uid);
        $criteria->add(ListInboxPeer::DEL_INDEX, $del_index);
        $v = ListInboxPeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseListInboxPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/ListInboxMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.ListInboxMapBuilder');
}

