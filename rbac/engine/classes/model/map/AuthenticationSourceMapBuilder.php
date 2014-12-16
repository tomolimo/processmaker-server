<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'AUTHENTICATION_SOURCE' table to 'rbac' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package  rbac-classes-model
 */
class AuthenticationSourceMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'classes.model.map.AuthenticationSourceMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('rbac');

		$tMap = $this->dbMap->addTable('RBAC_AUTHENTICATION_SOURCE');
		$tMap->setPhpName('AuthenticationSource');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('AUTH_SOURCE_UID', 'AuthSourceUid', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('AUTH_SOURCE_NAME', 'AuthSourceName', 'string', CreoleTypes::VARCHAR, true, 50);

		$tMap->addColumn('AUTH_SOURCE_PROVIDER', 'AuthSourceProvider', 'string', CreoleTypes::VARCHAR, true, 20);

		$tMap->addColumn('AUTH_SOURCE_SERVER_NAME', 'AuthSourceServerName', 'string', CreoleTypes::VARCHAR, true, 50);

		$tMap->addColumn('AUTH_SOURCE_PORT', 'AuthSourcePort', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('AUTH_SOURCE_ENABLED_TLS', 'AuthSourceEnabledTls', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('AUTH_SOURCE_VERSION', 'AuthSourceVersion', 'string', CreoleTypes::VARCHAR, true, 16);

		$tMap->addColumn('AUTH_SOURCE_BASE_DN', 'AuthSourceBaseDn', 'string', CreoleTypes::VARCHAR, true, 128);

		$tMap->addColumn('AUTH_ANONYMOUS', 'AuthAnonymous', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('AUTH_SOURCE_SEARCH_USER', 'AuthSourceSearchUser', 'string', CreoleTypes::VARCHAR, true, 128);

		$tMap->addColumn('AUTH_SOURCE_PASSWORD', 'AuthSourcePassword', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('AUTH_SOURCE_ATTRIBUTES', 'AuthSourceAttributes', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('AUTH_SOURCE_OBJECT_CLASSES', 'AuthSourceObjectClasses', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('AUTH_SOURCE_DATA', 'AuthSourceData', 'string', CreoleTypes::LONGVARCHAR, false, null);

	} // doBuild()

} // AuthenticationSourceMapBuilder
