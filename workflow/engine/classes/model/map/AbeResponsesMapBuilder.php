<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'ABE_RESPONSES' table to 'workflow' DatabaseMap object.
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
class AbeResponsesMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.AbeResponsesMapBuilder';

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

        $tMap = $this->dbMap->addTable('ABE_RESPONSES');
        $tMap->setPhpName('AbeResponses');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ABE_RES_UID', 'AbeResUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('ABE_REQ_UID', 'AbeReqUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('ABE_RES_CLIENT_IP', 'AbeResClientIp', 'string', CreoleTypes::VARCHAR, true, 20);

        $tMap->addColumn('ABE_RES_DATA', 'AbeResData', 'string', CreoleTypes::LONGVARCHAR, true, null);

        $tMap->addColumn('ABE_RES_DATE', 'AbeResDate', 'int', CreoleTypes::TIMESTAMP, true, null);

        $tMap->addColumn('ABE_RES_STATUS', 'AbeResStatus', 'string', CreoleTypes::VARCHAR, true, 10);

        $tMap->addColumn('ABE_RES_MESSAGE', 'AbeResMessage', 'string', CreoleTypes::VARCHAR, false, 255);

    } // doBuild()

} // AbeResponsesMapBuilder
