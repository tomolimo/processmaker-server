<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'UPGRADE_MANAGER' table to 'workflow' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    workflow.classes.model.map
 */
class UpgradeManagerMapBuilder
{
    /**
     * The (dot-path) name of this class
    */
    const CLASS_NAME = 'classes.model.map.UpgradeManagerMapBuilder';
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
        $this->dbMap = Propel::getDatabaseMap('workflow');

        $tMap = $this->dbMap->addTable('UPGRADE_MANAGER');

        $tMap->setPhpName('UpgradeManager');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('COMPONENT_NAME', 'ComponentName', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('COMPONENT_NICK', 'ComponentNick', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('COMPONENT_STATE', 'ComponentState', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('COMPONENT_STATUS', 'ComponentStatus', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('COMPONENT_VERSION', 'ComponentVersion', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('COMPONENT_TYPE', 'ComponentType', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('STORE_ID', 'StoreId', 'string', CreoleTypes::VARCHAR, true, 32);
    }
}

