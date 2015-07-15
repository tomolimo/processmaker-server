<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'ABE_REQUESTS' table to 'workflow' DatabaseMap object.
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
class AbeRequestsMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.AbeRequestsMapBuilder';

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

        $tMap = $this->dbMap->addTable('ABE_REQUESTS');
        $tMap->setPhpName('AbeRequests');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ABE_REQ_UID', 'AbeReqUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('ABE_UID', 'AbeUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('APP_UID', 'AppUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('DEL_INDEX', 'DelIndex', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('ABE_REQ_SENT_TO', 'AbeReqSentTo', 'string', CreoleTypes::VARCHAR, true, 100);

        $tMap->addColumn('ABE_REQ_SUBJECT', 'AbeReqSubject', 'string', CreoleTypes::VARCHAR, true, 150);

        $tMap->addColumn('ABE_REQ_BODY', 'AbeReqBody', 'string', CreoleTypes::LONGVARCHAR, true, null);

        $tMap->addColumn('ABE_REQ_DATE', 'AbeReqDate', 'int', CreoleTypes::TIMESTAMP, true, null);

        $tMap->addColumn('ABE_REQ_STATUS', 'AbeReqStatus', 'string', CreoleTypes::VARCHAR, false, 10);

        $tMap->addColumn('ABE_REQ_ANSWERED', 'AbeReqAnswered', 'int', CreoleTypes::TINYINT, true, null);

    } // doBuild()

} // AbeRequestsMapBuilder
