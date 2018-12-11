<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by ListMyInboxPeer::getOMClass()
include_once 'classes/model/ListMyInbox.php';

/**
 * Base static class for performing query and update operations on the 'LIST_MY_INBOX' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseListMyInboxPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'workflow';

    /** the table name for this class */
    const TABLE_NAME = 'LIST_MY_INBOX';

    /** A class that can be returned by this peer. */
    const CLASS_DEFAULT = 'classes.model.ListMyInbox';

    /** The total number of columns. */
    const NUM_COLUMNS = 29;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;


    /** the column name for the APP_UID field */
    const APP_UID = 'LIST_MY_INBOX.APP_UID';

    /** the column name for the USR_UID field */
    const USR_UID = 'LIST_MY_INBOX.USR_UID';

    /** the column name for the TAS_UID field */
    const TAS_UID = 'LIST_MY_INBOX.TAS_UID';

    /** the column name for the PRO_UID field */
    const PRO_UID = 'LIST_MY_INBOX.PRO_UID';

    /** the column name for the APP_NUMBER field */
    const APP_NUMBER = 'LIST_MY_INBOX.APP_NUMBER';

    /** the column name for the APP_TITLE field */
    const APP_TITLE = 'LIST_MY_INBOX.APP_TITLE';

    /** the column name for the APP_PRO_TITLE field */
    const APP_PRO_TITLE = 'LIST_MY_INBOX.APP_PRO_TITLE';

    /** the column name for the APP_TAS_TITLE field */
    const APP_TAS_TITLE = 'LIST_MY_INBOX.APP_TAS_TITLE';

    /** the column name for the APP_CREATE_DATE field */
    const APP_CREATE_DATE = 'LIST_MY_INBOX.APP_CREATE_DATE';

    /** the column name for the APP_UPDATE_DATE field */
    const APP_UPDATE_DATE = 'LIST_MY_INBOX.APP_UPDATE_DATE';

    /** the column name for the APP_FINISH_DATE field */
    const APP_FINISH_DATE = 'LIST_MY_INBOX.APP_FINISH_DATE';

    /** the column name for the APP_STATUS field */
    const APP_STATUS = 'LIST_MY_INBOX.APP_STATUS';

    /** the column name for the DEL_INDEX field */
    const DEL_INDEX = 'LIST_MY_INBOX.DEL_INDEX';

    /** the column name for the DEL_PREVIOUS_USR_UID field */
    const DEL_PREVIOUS_USR_UID = 'LIST_MY_INBOX.DEL_PREVIOUS_USR_UID';

    /** the column name for the DEL_PREVIOUS_USR_USERNAME field */
    const DEL_PREVIOUS_USR_USERNAME = 'LIST_MY_INBOX.DEL_PREVIOUS_USR_USERNAME';

    /** the column name for the DEL_PREVIOUS_USR_FIRSTNAME field */
    const DEL_PREVIOUS_USR_FIRSTNAME = 'LIST_MY_INBOX.DEL_PREVIOUS_USR_FIRSTNAME';

    /** the column name for the DEL_PREVIOUS_USR_LASTNAME field */
    const DEL_PREVIOUS_USR_LASTNAME = 'LIST_MY_INBOX.DEL_PREVIOUS_USR_LASTNAME';

    /** the column name for the DEL_CURRENT_USR_UID field */
    const DEL_CURRENT_USR_UID = 'LIST_MY_INBOX.DEL_CURRENT_USR_UID';

    /** the column name for the DEL_CURRENT_USR_USERNAME field */
    const DEL_CURRENT_USR_USERNAME = 'LIST_MY_INBOX.DEL_CURRENT_USR_USERNAME';

    /** the column name for the DEL_CURRENT_USR_FIRSTNAME field */
    const DEL_CURRENT_USR_FIRSTNAME = 'LIST_MY_INBOX.DEL_CURRENT_USR_FIRSTNAME';

    /** the column name for the DEL_CURRENT_USR_LASTNAME field */
    const DEL_CURRENT_USR_LASTNAME = 'LIST_MY_INBOX.DEL_CURRENT_USR_LASTNAME';

    /** the column name for the DEL_DELEGATE_DATE field */
    const DEL_DELEGATE_DATE = 'LIST_MY_INBOX.DEL_DELEGATE_DATE';

    /** the column name for the DEL_INIT_DATE field */
    const DEL_INIT_DATE = 'LIST_MY_INBOX.DEL_INIT_DATE';

    /** the column name for the DEL_DUE_DATE field */
    const DEL_DUE_DATE = 'LIST_MY_INBOX.DEL_DUE_DATE';

    /** the column name for the DEL_PRIORITY field */
    const DEL_PRIORITY = 'LIST_MY_INBOX.DEL_PRIORITY';

    /** the column name for the PRO_ID field */
    const PRO_ID = 'LIST_MY_INBOX.PRO_ID';

    /** the column name for the USR_ID field */
    const USR_ID = 'LIST_MY_INBOX.USR_ID';

    /** the column name for the TAS_ID field */
    const TAS_ID = 'LIST_MY_INBOX.TAS_ID';

    /** the column name for the APP_STATUS_ID field */
    const APP_STATUS_ID = 'LIST_MY_INBOX.APP_STATUS_ID';

    /** The PHP to DB Name Mapping */
    private static $phpNameMap = null;


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    private static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid', 'UsrUid', 'TasUid', 'ProUid', 'AppNumber', 'AppTitle', 'AppProTitle', 'AppTasTitle', 'AppCreateDate', 'AppUpdateDate', 'AppFinishDate', 'AppStatus', 'DelIndex', 'DelPreviousUsrUid', 'DelPreviousUsrUsername', 'DelPreviousUsrFirstname', 'DelPreviousUsrLastname', 'DelCurrentUsrUid', 'DelCurrentUsrUsername', 'DelCurrentUsrFirstname', 'DelCurrentUsrLastname', 'DelDelegateDate', 'DelInitDate', 'DelDueDate', 'DelPriority', 'ProId', 'UsrId', 'TasId', 'AppStatusId', ),
        BasePeer::TYPE_COLNAME => array (ListMyInboxPeer::APP_UID, ListMyInboxPeer::USR_UID, ListMyInboxPeer::TAS_UID, ListMyInboxPeer::PRO_UID, ListMyInboxPeer::APP_NUMBER, ListMyInboxPeer::APP_TITLE, ListMyInboxPeer::APP_PRO_TITLE, ListMyInboxPeer::APP_TAS_TITLE, ListMyInboxPeer::APP_CREATE_DATE, ListMyInboxPeer::APP_UPDATE_DATE, ListMyInboxPeer::APP_FINISH_DATE, ListMyInboxPeer::APP_STATUS, ListMyInboxPeer::DEL_INDEX, ListMyInboxPeer::DEL_PREVIOUS_USR_UID, ListMyInboxPeer::DEL_PREVIOUS_USR_USERNAME, ListMyInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME, ListMyInboxPeer::DEL_PREVIOUS_USR_LASTNAME, ListMyInboxPeer::DEL_CURRENT_USR_UID, ListMyInboxPeer::DEL_CURRENT_USR_USERNAME, ListMyInboxPeer::DEL_CURRENT_USR_FIRSTNAME, ListMyInboxPeer::DEL_CURRENT_USR_LASTNAME, ListMyInboxPeer::DEL_DELEGATE_DATE, ListMyInboxPeer::DEL_INIT_DATE, ListMyInboxPeer::DEL_DUE_DATE, ListMyInboxPeer::DEL_PRIORITY, ListMyInboxPeer::PRO_ID, ListMyInboxPeer::USR_ID, ListMyInboxPeer::TAS_ID, ListMyInboxPeer::APP_STATUS_ID, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID', 'USR_UID', 'TAS_UID', 'PRO_UID', 'APP_NUMBER', 'APP_TITLE', 'APP_PRO_TITLE', 'APP_TAS_TITLE', 'APP_CREATE_DATE', 'APP_UPDATE_DATE', 'APP_FINISH_DATE', 'APP_STATUS', 'DEL_INDEX', 'DEL_PREVIOUS_USR_UID', 'DEL_PREVIOUS_USR_USERNAME', 'DEL_PREVIOUS_USR_FIRSTNAME', 'DEL_PREVIOUS_USR_LASTNAME', 'DEL_CURRENT_USR_UID', 'DEL_CURRENT_USR_USERNAME', 'DEL_CURRENT_USR_FIRSTNAME', 'DEL_CURRENT_USR_LASTNAME', 'DEL_DELEGATE_DATE', 'DEL_INIT_DATE', 'DEL_DUE_DATE', 'DEL_PRIORITY', 'PRO_ID', 'USR_ID', 'TAS_ID', 'APP_STATUS_ID', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    private static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('AppUid' => 0, 'UsrUid' => 1, 'TasUid' => 2, 'ProUid' => 3, 'AppNumber' => 4, 'AppTitle' => 5, 'AppProTitle' => 6, 'AppTasTitle' => 7, 'AppCreateDate' => 8, 'AppUpdateDate' => 9, 'AppFinishDate' => 10, 'AppStatus' => 11, 'DelIndex' => 12, 'DelPreviousUsrUid' => 13, 'DelPreviousUsrUsername' => 14, 'DelPreviousUsrFirstname' => 15, 'DelPreviousUsrLastname' => 16, 'DelCurrentUsrUid' => 17, 'DelCurrentUsrUsername' => 18, 'DelCurrentUsrFirstname' => 19, 'DelCurrentUsrLastname' => 20, 'DelDelegateDate' => 21, 'DelInitDate' => 22, 'DelDueDate' => 23, 'DelPriority' => 24, 'ProId' => 25, 'UsrId' => 26, 'TasId' => 27, 'AppStatusId' => 28, ),
        BasePeer::TYPE_COLNAME => array (ListMyInboxPeer::APP_UID => 0, ListMyInboxPeer::USR_UID => 1, ListMyInboxPeer::TAS_UID => 2, ListMyInboxPeer::PRO_UID => 3, ListMyInboxPeer::APP_NUMBER => 4, ListMyInboxPeer::APP_TITLE => 5, ListMyInboxPeer::APP_PRO_TITLE => 6, ListMyInboxPeer::APP_TAS_TITLE => 7, ListMyInboxPeer::APP_CREATE_DATE => 8, ListMyInboxPeer::APP_UPDATE_DATE => 9, ListMyInboxPeer::APP_FINISH_DATE => 10, ListMyInboxPeer::APP_STATUS => 11, ListMyInboxPeer::DEL_INDEX => 12, ListMyInboxPeer::DEL_PREVIOUS_USR_UID => 13, ListMyInboxPeer::DEL_PREVIOUS_USR_USERNAME => 14, ListMyInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME => 15, ListMyInboxPeer::DEL_PREVIOUS_USR_LASTNAME => 16, ListMyInboxPeer::DEL_CURRENT_USR_UID => 17, ListMyInboxPeer::DEL_CURRENT_USR_USERNAME => 18, ListMyInboxPeer::DEL_CURRENT_USR_FIRSTNAME => 19, ListMyInboxPeer::DEL_CURRENT_USR_LASTNAME => 20, ListMyInboxPeer::DEL_DELEGATE_DATE => 21, ListMyInboxPeer::DEL_INIT_DATE => 22, ListMyInboxPeer::DEL_DUE_DATE => 23, ListMyInboxPeer::DEL_PRIORITY => 24, ListMyInboxPeer::PRO_ID => 25, ListMyInboxPeer::USR_ID => 26, ListMyInboxPeer::TAS_ID => 27, ListMyInboxPeer::APP_STATUS_ID => 28, ),
        BasePeer::TYPE_FIELDNAME => array ('APP_UID' => 0, 'USR_UID' => 1, 'TAS_UID' => 2, 'PRO_UID' => 3, 'APP_NUMBER' => 4, 'APP_TITLE' => 5, 'APP_PRO_TITLE' => 6, 'APP_TAS_TITLE' => 7, 'APP_CREATE_DATE' => 8, 'APP_UPDATE_DATE' => 9, 'APP_FINISH_DATE' => 10, 'APP_STATUS' => 11, 'DEL_INDEX' => 12, 'DEL_PREVIOUS_USR_UID' => 13, 'DEL_PREVIOUS_USR_USERNAME' => 14, 'DEL_PREVIOUS_USR_FIRSTNAME' => 15, 'DEL_PREVIOUS_USR_LASTNAME' => 16, 'DEL_CURRENT_USR_UID' => 17, 'DEL_CURRENT_USR_USERNAME' => 18, 'DEL_CURRENT_USR_FIRSTNAME' => 19, 'DEL_CURRENT_USR_LASTNAME' => 20, 'DEL_DELEGATE_DATE' => 21, 'DEL_INIT_DATE' => 22, 'DEL_DUE_DATE' => 23, 'DEL_PRIORITY' => 24, 'PRO_ID' => 25, 'USR_ID' => 26, 'TAS_ID' => 27, 'APP_STATUS_ID' => 28, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, )
    );

    /**
     * @return     MapBuilder the map builder for this peer
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function getMapBuilder()
    {
        include_once 'classes/model/map/ListMyInboxMapBuilder.php';
        return BasePeer::getMapBuilder('classes.model.map.ListMyInboxMapBuilder');
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
            $map = ListMyInboxPeer::getTableMap();
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
     * @param      string $column The column name for current table. (i.e. ListMyInboxPeer::COLUMN_NAME).
     * @return     string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ListMyInboxPeer::TABLE_NAME.'.', $alias.'.', $column);
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

        $criteria->addSelectColumn(ListMyInboxPeer::APP_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::USR_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::TAS_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::PRO_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_NUMBER);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_TITLE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_PRO_TITLE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_TAS_TITLE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_CREATE_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_UPDATE_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_FINISH_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_STATUS);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_INDEX);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_USERNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_LASTNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_UID);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_USERNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_FIRSTNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_LASTNAME);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_DELEGATE_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_INIT_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_DUE_DATE);

        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PRIORITY);

        $criteria->addSelectColumn(ListMyInboxPeer::PRO_ID);

        $criteria->addSelectColumn(ListMyInboxPeer::USR_ID);

        $criteria->addSelectColumn(ListMyInboxPeer::TAS_ID);

        $criteria->addSelectColumn(ListMyInboxPeer::APP_STATUS_ID);

    }

    const COUNT = 'COUNT(LIST_MY_INBOX.APP_UID)';
    const COUNT_DISTINCT = 'COUNT(DISTINCT LIST_MY_INBOX.APP_UID)';

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
            $criteria->addSelectColumn(ListMyInboxPeer::COUNT_DISTINCT);
        } else {
            $criteria->addSelectColumn(ListMyInboxPeer::COUNT);
        }

        // just in case we're grouping: add those columns to the select statement
        foreach ($criteria->getGroupByColumns() as $column) {
            $criteria->addSelectColumn($column);
        }

        $rs = ListMyInboxPeer::doSelectRS($criteria, $con);
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
     * @return     ListMyInbox
     * @throws     PropelException Any exceptions caught during processing will be
     *       rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ListMyInboxPeer::doSelect($critcopy, $con);
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
        return ListMyInboxPeer::populateObjects(ListMyInboxPeer::doSelectRS($criteria, $con));
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
            ListMyInboxPeer::addSelectColumns($criteria);
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
        $cls = ListMyInboxPeer::getOMClass();
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
        return ListMyInboxPeer::CLASS_DEFAULT;
    }

    /**
     * Method perform an INSERT on the database, given a ListMyInbox or Criteria object.
     *
     * @param      mixed $values Criteria or ListMyInbox object containing data that is used to create the INSERT statement.
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
            $criteria = $values->buildCriteria(); // build Criteria from ListMyInbox object
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
     * Method perform an UPDATE on the database, given a ListMyInbox or Criteria object.
     *
     * @param      mixed $values Criteria or ListMyInbox object containing data create the UPDATE statement.
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

            $comparison = $criteria->getComparison(ListMyInboxPeer::APP_UID);
            $selectCriteria->add(ListMyInboxPeer::APP_UID, $criteria->remove(ListMyInboxPeer::APP_UID), $comparison);

        } else {
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(self::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Method to DELETE all rows from the LIST_MY_INBOX table.
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
            $affectedRows += BasePeer::doDeleteAll(ListMyInboxPeer::TABLE_NAME, $con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Method perform a DELETE on the database, given a ListMyInbox or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ListMyInbox object or primary key or array of primary keys
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
            $con = Propel::getConnection(ListMyInboxPeer::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } elseif ($values instanceof ListMyInbox) {

            $criteria = $values->buildPkeyCriteria();
        } else {
            // it must be the primary key
            $criteria = new Criteria(self::DATABASE_NAME);
            $criteria->add(ListMyInboxPeer::APP_UID, (array) $values, Criteria::IN);
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
     * Validates all modified columns of given ListMyInbox object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      ListMyInbox $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate(ListMyInbox $obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ListMyInboxPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ListMyInboxPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ListMyInboxPeer::DATABASE_NAME, ListMyInboxPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      mixed $pk the primary key.
     * @param      Connection $con the connection to use
     * @return     ListMyInbox
     */
    public static function retrieveByPK($pk, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(self::DATABASE_NAME);
        }

        $criteria = new Criteria(ListMyInboxPeer::DATABASE_NAME);

        $criteria->add(ListMyInboxPeer::APP_UID, $pk);


        $v = ListMyInboxPeer::doSelect($criteria, $con);

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
            $criteria->add(ListMyInboxPeer::APP_UID, $pks, Criteria::IN);
            $objs = ListMyInboxPeer::doSelect($criteria, $con);
        }
        return $objs;
    }
}


// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
    // the MapBuilder classes register themselves with Propel during initialization
    // so we need to load them here.
    try {
        BaseListMyInboxPeer::getMapBuilder();
    } catch (Exception $e) {
        Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
    }
} else {
    // even if Propel is not yet initialized, the map builder class can be registered
    // now and then it will be loaded when Propel initializes.
    require_once 'classes/model/map/ListMyInboxMapBuilder.php';
    Propel::registerMapBuilder('classes.model.map.ListMyInboxMapBuilder');
}

