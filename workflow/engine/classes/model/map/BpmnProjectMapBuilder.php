<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'BPMN_PROJECT' table to 'workflow' DatabaseMap object.
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
class BpmnProjectMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.BpmnProjectMapBuilder';

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

        $tMap = $this->dbMap->addTable('BPMN_PROJECT');
        $tMap->setPhpName('BpmnProject');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('PRJ_NAME', 'PrjName', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('PRJ_DESCRIPTION', 'PrjDescription', 'string', CreoleTypes::VARCHAR, false, 512);

        $tMap->addColumn('PRJ_TARGET_NAMESPACE', 'PrjTargetNamespace', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_EXPRESION_LANGUAGE', 'PrjExpresionLanguage', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_TYPE_LANGUAGE', 'PrjTypeLanguage', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_EXPORTER', 'PrjExporter', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_EXPORTER_VERSION', 'PrjExporterVersion', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_CREATE_DATE', 'PrjCreateDate', 'int', CreoleTypes::TIMESTAMP, true, null);

        $tMap->addColumn('PRJ_UPDATE_DATE', 'PrjUpdateDate', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('PRJ_AUTHOR', 'PrjAuthor', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_AUTHOR_VERSION', 'PrjAuthorVersion', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('PRJ_ORIGINAL_SOURCE', 'PrjOriginalSource', 'string', CreoleTypes::LONGVARCHAR, false, null);

    } // doBuild()

} // BpmnProjectMapBuilder
