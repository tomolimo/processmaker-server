<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'BPMN_FLOW' table to 'workflow' DatabaseMap object.
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
class BpmnFlowMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.BpmnFlowMapBuilder';

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

        $tMap = $this->dbMap->addTable('BPMN_FLOW');
        $tMap->setPhpName('BpmnFlow');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('FLO_UID', 'FloUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addForeignKey('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROJECT', 'PRJ_UID', true, 32);

        $tMap->addForeignKey('DIA_UID', 'DiaUid', 'string', CreoleTypes::VARCHAR, 'BPMN_DIAGRAM', 'DIA_UID', true, 32);

        $tMap->addColumn('FLO_TYPE', 'FloType', 'string', CreoleTypes::VARCHAR, true, 20);

        $tMap->addColumn('FLO_NAME', 'FloName', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('FLO_ELEMENT_ORIGIN', 'FloElementOrigin', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('FLO_ELEMENT_ORIGIN_TYPE', 'FloElementOriginType', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('FLO_ELEMENT_ORIGIN_PORT', 'FloElementOriginPort', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_ELEMENT_DEST', 'FloElementDest', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('FLO_ELEMENT_DEST_TYPE', 'FloElementDestType', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('FLO_ELEMENT_DEST_PORT', 'FloElementDestPort', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_IS_INMEDIATE', 'FloIsInmediate', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('FLO_CONDITION', 'FloCondition', 'string', CreoleTypes::VARCHAR, false, 512);

        $tMap->addColumn('FLO_X1', 'FloX1', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_Y1', 'FloY1', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_X2', 'FloX2', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_Y2', 'FloY2', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('FLO_STATE', 'FloState', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('FLO_POSITION', 'FloPosition', 'int', CreoleTypes::INTEGER, true, null);

    } // doBuild()

} // BpmnFlowMapBuilder
