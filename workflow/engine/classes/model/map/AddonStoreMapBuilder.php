<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'ADDON_STORE' table to 'propel' DatabaseMap object.
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
class AddonStoreMapBuilder
{
    /**
     * The (dot-path) name of this class
    */
    const CLASS_NAME = 'classes.model.map.AddonStoreMapBuilder';

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
     * @return the databasemap
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
        $this->dbMap = Propel::getDatabaseMap('propel');
        $tMap = $this->dbMap->addTable('ADDON_STORE');
        $tMap->setPhpName('AddonStore');

        $tMap->setUseIdGenerator(false);
        $tMap->addPrimaryKey('STORE_ID', 'StoreId', 'string', CreoleTypes::VARCHAR, true, 32);
        $tMap->addColumn('STORE_VERSION', 'StoreVersion', 'int', CreoleTypes::INTEGER, false, null);
        $tMap->addColumn('STORE_LOCATION', 'StoreLocation', 'string', CreoleTypes::VARCHAR, true, 2048);
        $tMap->addColumn('LAST_UPDATED', 'LastUpdated', 'int', CreoleTypes::TIMESTAMP, false, null);
    }
}

