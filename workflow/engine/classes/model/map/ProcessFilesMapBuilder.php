<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'PROCESS_FILES' table to 'workflow' DatabaseMap object.
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
class ProcessFilesMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.ProcessFilesMapBuilder';

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

        $tMap = $this->dbMap->addTable('PROCESS_FILES');
        $tMap->setPhpName('ProcessFiles');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('PRF_UID', 'PrfUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('PRO_UID', 'ProUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('USR_UID', 'UsrUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('PRF_UPDATE_USR_UID', 'PrfUpdateUsrUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('PRF_PATH', 'PrfPath', 'string', CreoleTypes::VARCHAR, true, 256);

        $tMap->addColumn('PRF_TYPE', 'PrfType', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('PRF_EDITABLE', 'PrfEditable', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('PRF_CREATE_DATE', 'PrfCreateDate', 'int', CreoleTypes::TIMESTAMP, true, null);

        $tMap->addColumn('PRF_UPDATE_DATE', 'PrfUpdateDate', 'int', CreoleTypes::TIMESTAMP, false, null);

    } // doBuild()

} // ProcessFilesMapBuilder
