<?php

require_once 'propel/util/BasePeer.php';
// The object class -- needed for instanceof checks in this class.
// actual class may be a subclass -- as returned by AuthenticationSourcePeer::getOMClass()
include_once 'classes/model/AuthenticationSource.php';

/**
 * Base static class for performing query and update operations on the 'AUTHENTICATION_SOURCE' table.
 *
 * 
 *
 * @package  rbac-classes-model
 */
abstract class BaseAuthenticationSourcePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'rbac';

	/** the table name for this class */
	const TABLE_NAME = 'RBAC_AUTHENTICATION_SOURCE';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'classes.model.AuthenticationSource';

	/** The total number of columns. */
	const NUM_COLUMNS = 14;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the AUTH_SOURCE_UID field */
	const AUTH_SOURCE_UID = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_UID';

	/** the column name for the AUTH_SOURCE_NAME field */
	const AUTH_SOURCE_NAME = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_NAME';

	/** the column name for the AUTH_SOURCE_PROVIDER field */
	const AUTH_SOURCE_PROVIDER = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_PROVIDER';

	/** the column name for the AUTH_SOURCE_SERVER_NAME field */
	const AUTH_SOURCE_SERVER_NAME = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_SERVER_NAME';

	/** the column name for the AUTH_SOURCE_PORT field */
	const AUTH_SOURCE_PORT = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_PORT';

	/** the column name for the AUTH_SOURCE_ENABLED_TLS field */
	const AUTH_SOURCE_ENABLED_TLS = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_ENABLED_TLS';

	/** the column name for the AUTH_SOURCE_VERSION field */
	const AUTH_SOURCE_VERSION = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_VERSION';

	/** the column name for the AUTH_SOURCE_BASE_DN field */
	const AUTH_SOURCE_BASE_DN = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_BASE_DN';

	/** the column name for the AUTH_ANONYMOUS field */
	const AUTH_ANONYMOUS = 'RBAC_AUTHENTICATION_SOURCE.AUTH_ANONYMOUS';

	/** the column name for the AUTH_SOURCE_SEARCH_USER field */
	const AUTH_SOURCE_SEARCH_USER = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_SEARCH_USER';

	/** the column name for the AUTH_SOURCE_PASSWORD field */
	const AUTH_SOURCE_PASSWORD = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_PASSWORD';

	/** the column name for the AUTH_SOURCE_ATTRIBUTES field */
	const AUTH_SOURCE_ATTRIBUTES = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_ATTRIBUTES';

	/** the column name for the AUTH_SOURCE_OBJECT_CLASSES field */
	const AUTH_SOURCE_OBJECT_CLASSES = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_OBJECT_CLASSES';

	/** the column name for the AUTH_SOURCE_DATA field */
	const AUTH_SOURCE_DATA = 'RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_DATA';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('AuthSourceUid', 'AuthSourceName', 'AuthSourceProvider', 'AuthSourceServerName', 'AuthSourcePort', 'AuthSourceEnabledTls', 'AuthSourceVersion', 'AuthSourceBaseDn', 'AuthAnonymous', 'AuthSourceSearchUser', 'AuthSourcePassword', 'AuthSourceAttributes', 'AuthSourceObjectClasses', 'AuthSourceData', ),
		BasePeer::TYPE_COLNAME => array (AuthenticationSourcePeer::AUTH_SOURCE_UID, AuthenticationSourcePeer::AUTH_SOURCE_NAME, AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER, AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME, AuthenticationSourcePeer::AUTH_SOURCE_PORT, AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS, AuthenticationSourcePeer::AUTH_SOURCE_VERSION, AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN, AuthenticationSourcePeer::AUTH_ANONYMOUS, AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER, AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD, AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES, AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES, AuthenticationSourcePeer::AUTH_SOURCE_DATA, ),
		BasePeer::TYPE_FIELDNAME => array ('AUTH_SOURCE_UID', 'AUTH_SOURCE_NAME', 'AUTH_SOURCE_PROVIDER', 'AUTH_SOURCE_SERVER_NAME', 'AUTH_SOURCE_PORT', 'AUTH_SOURCE_ENABLED_TLS', 'AUTH_SOURCE_VERSION', 'AUTH_SOURCE_BASE_DN', 'AUTH_ANONYMOUS', 'AUTH_SOURCE_SEARCH_USER', 'AUTH_SOURCE_PASSWORD', 'AUTH_SOURCE_ATTRIBUTES', 'AUTH_SOURCE_OBJECT_CLASSES', 'AUTH_SOURCE_DATA', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('AuthSourceUid' => 0, 'AuthSourceName' => 1, 'AuthSourceProvider' => 2, 'AuthSourceServerName' => 3, 'AuthSourcePort' => 4, 'AuthSourceEnabledTls' => 5, 'AuthSourceVersion' => 6, 'AuthSourceBaseDn' => 7, 'AuthAnonymous' => 8, 'AuthSourceSearchUser' => 9, 'AuthSourcePassword' => 10, 'AuthSourceAttributes' => 11, 'AuthSourceObjectClasses' => 12, 'AuthSourceData' => 13, ),
		BasePeer::TYPE_COLNAME => array (AuthenticationSourcePeer::AUTH_SOURCE_UID => 0, AuthenticationSourcePeer::AUTH_SOURCE_NAME => 1, AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER => 2, AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME => 3, AuthenticationSourcePeer::AUTH_SOURCE_PORT => 4, AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS => 5, AuthenticationSourcePeer::AUTH_SOURCE_VERSION => 6, AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN => 7, AuthenticationSourcePeer::AUTH_ANONYMOUS => 8, AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER => 9, AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD => 10, AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES => 11, AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES => 12, AuthenticationSourcePeer::AUTH_SOURCE_DATA => 13, ),
		BasePeer::TYPE_FIELDNAME => array ('AUTH_SOURCE_UID' => 0, 'AUTH_SOURCE_NAME' => 1, 'AUTH_SOURCE_PROVIDER' => 2, 'AUTH_SOURCE_SERVER_NAME' => 3, 'AUTH_SOURCE_PORT' => 4, 'AUTH_SOURCE_ENABLED_TLS' => 5, 'AUTH_SOURCE_VERSION' => 6, 'AUTH_SOURCE_BASE_DN' => 7, 'AUTH_ANONYMOUS' => 8, 'AUTH_SOURCE_SEARCH_USER' => 9, 'AUTH_SOURCE_PASSWORD' => 10, 'AUTH_SOURCE_ATTRIBUTES' => 11, 'AUTH_SOURCE_OBJECT_CLASSES' => 12, 'AUTH_SOURCE_DATA' => 13, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'classes/model/map/AuthenticationSourceMapBuilder.php';
		return BasePeer::getMapBuilder('classes.model.map.AuthenticationSourceMapBuilder');
	}
	/**
	 * Gets a map (hash) of PHP names to DB column names.
	 *
	 * @return     array The PHP to DB name map for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
	 */
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = AuthenticationSourcePeer::getTableMap();
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
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. AuthenticationSourcePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(AuthenticationSourcePeer::TABLE_NAME.'.', $alias.'.', $column);
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
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_UID);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_NAME);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_PORT);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_VERSION);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_ANONYMOUS);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES);

		$criteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_DATA);

	}

	const COUNT = 'COUNT(RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_UID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT RBAC_AUTHENTICATION_SOURCE.AUTH_SOURCE_UID)';

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
			$criteria->addSelectColumn(AuthenticationSourcePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(AuthenticationSourcePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = AuthenticationSourcePeer::doSelectRS($criteria, $con);
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
	 * @return     AuthenticationSource
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = AuthenticationSourcePeer::doSelect($critcopy, $con);
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
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return AuthenticationSourcePeer::populateObjects(AuthenticationSourcePeer::doSelectRS($criteria, $con));
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
	 *		 rethrown wrapped into a PropelException.
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
			AuthenticationSourcePeer::addSelectColumns($criteria);
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
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = AuthenticationSourcePeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
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
	 *		 rethrown wrapped into a PropelException.
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
		return AuthenticationSourcePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a AuthenticationSource or Criteria object.
	 *
	 * @param      mixed $values Criteria or AuthenticationSource object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from AuthenticationSource object
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a AuthenticationSource or Criteria object.
	 *
	 * @param      mixed $values Criteria or AuthenticationSource object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(AuthenticationSourcePeer::AUTH_SOURCE_UID);
			$selectCriteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, $criteria->remove(AuthenticationSourcePeer::AUTH_SOURCE_UID), $comparison);

		} else { // $values is AuthenticationSource object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the AUTHENTICATION_SOURCE table.
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
			$affectedRows += BasePeer::doDeleteAll(AuthenticationSourcePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a AuthenticationSource or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or AuthenticationSource object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      Connection $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof AuthenticationSource) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given AuthenticationSource object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      AuthenticationSource $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(AuthenticationSource $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(AuthenticationSourcePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(AuthenticationSourcePeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(AuthenticationSourcePeer::DATABASE_NAME, AuthenticationSourcePeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     AuthenticationSource
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(AuthenticationSourcePeer::DATABASE_NAME);

		$criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, $pk);


		$v = AuthenticationSourcePeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
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
			$criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, $pks, Criteria::IN);
			$objs = AuthenticationSourcePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseAuthenticationSourcePeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseAuthenticationSourcePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'classes/model/map/AuthenticationSourceMapBuilder.php';
	Propel::registerMapBuilder('classes.model.map.AuthenticationSourceMapBuilder');
}
