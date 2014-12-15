<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'PERMISSIONS' table to 'rbac' DatabaseMap object.
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
class PermissionsMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'classes.model.map.PermissionsMapBuilder';

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

		$tMap = $this->dbMap->addTable('RBAC_PERMISSIONS');
		$tMap->setPhpName('Permissions');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('PER_UID', 'PerUid', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('PER_CODE', 'PerCode', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('PER_CREATE_DATE', 'PerCreateDate', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('PER_UPDATE_DATE', 'PerUpdateDate', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('PER_STATUS', 'PerStatus', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('PER_SYSTEM', 'PerSystem', 'string', CreoleTypes::VARCHAR, true, 32);

	} // doBuild()

} // PermissionsMapBuilder
