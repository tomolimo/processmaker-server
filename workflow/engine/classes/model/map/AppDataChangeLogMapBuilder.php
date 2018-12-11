<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'APP_DATA_CHANGE_LOG' table to 'workflow' DatabaseMap object.
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
class AppDataChangeLogMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.AppDataChangeLogMapBuilder';

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

        $tMap = $this->dbMap->addTable('APP_DATA_CHANGE_LOG');
        $tMap->setPhpName('AppDataChangeLog');

        $tMap->setUseIdGenerator(true);

        $tMap->addPrimaryKey('CHANGE_LOG_ID', 'ChangeLogId', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('DATE', 'Date', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('APP_NUMBER', 'AppNumber', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('DEL_INDEX', 'DelIndex', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('PRO_ID', 'ProId', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('TAS_ID', 'TasId', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('USR_ID', 'UsrId', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('OBJECT_TYPE', 'ObjectType', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('OBJECT_ID', 'ObjectId', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('OBJECT_UID', 'ObjectUid', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('EXECUTED_AT', 'ExecutedAt', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('SOURCE_ID', 'SourceId', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('DATA', 'Data', 'string', CreoleTypes::LONGVARCHAR, true, null);

        $tMap->addColumn('SKIN', 'Skin', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('LANGUAGE', 'Language', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('ROW_MIGRATION', 'RowMigration', 'int', CreoleTypes::INTEGER, false, null);

    } // doBuild()

} // AppDataChangeLogMapBuilder
