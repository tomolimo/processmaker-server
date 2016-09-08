<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'GMAIL_RELABELING' table to 'workflow' DatabaseMap object.
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
class GmailRelabelingMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.GmailRelabelingMapBuilder';

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

        $tMap = $this->dbMap->addTable('GMAIL_RELABELING');
        $tMap->setPhpName('GmailRelabeling');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('LABELING_UID', 'LabelingUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('CREATE_DATE', 'CreateDate', 'int', CreoleTypes::TIMESTAMP, true, null);

        $tMap->addColumn('APP_UID', 'AppUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('DEL_INDEX', 'DelIndex', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('CURRENT_LAST_INDEX', 'CurrentLastIndex', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('UNASSIGNED', 'Unassigned', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('STATUS', 'Status', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('MSG_ERROR', 'MsgError', 'string', CreoleTypes::LONGVARCHAR, false, null);

    } // doBuild()

} // GmailRelabelingMapBuilder
