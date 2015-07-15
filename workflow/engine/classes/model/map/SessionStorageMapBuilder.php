<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'SESSION_STORAGE' table to 'workflow' DatabaseMap object.
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
class SessionStorageMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.SessionStorageMapBuilder';

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

        $tMap = $this->dbMap->addTable('SESSION_STORAGE');
        $tMap->setPhpName('SessionStorage');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ID', 'Id', 'string', CreoleTypes::VARCHAR, true, 128);

        $tMap->addColumn('SET_TIME', 'SetTime', 'string', CreoleTypes::VARCHAR, true, 10);

        $tMap->addColumn('DATA', 'Data', 'string', CreoleTypes::LONGVARCHAR, true, null);

        $tMap->addColumn('SESSION_KEY', 'SessionKey', 'string', CreoleTypes::VARCHAR, true, 128);

        $tMap->addColumn('CLIENT_ADDRESS', 'ClientAddress', 'string', CreoleTypes::VARCHAR, false, 32);

    } // doBuild()

} // SessionStorageMapBuilder
