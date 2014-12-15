<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'BPMN_ACTIVITY' table to 'workflow' DatabaseMap object.
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
class BpmnActivityMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.BpmnActivityMapBuilder';

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

        $tMap = $this->dbMap->addTable('BPMN_ACTIVITY');
        $tMap->setPhpName('BpmnActivity');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ACT_UID', 'ActUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addForeignKey('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROJECT', 'PRJ_UID', true, 32);

        $tMap->addForeignKey('PRO_UID', 'ProUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROCESS', 'PRO_UID', false, 32);

        $tMap->addColumn('ACT_NAME', 'ActName', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ACT_TYPE', 'ActType', 'string', CreoleTypes::VARCHAR, true, 30);

        $tMap->addColumn('ACT_IS_FOR_COMPENSATION', 'ActIsForCompensation', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_START_QUANTITY', 'ActStartQuantity', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('ACT_COMPLETION_QUANTITY', 'ActCompletionQuantity', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('ACT_TASK_TYPE', 'ActTaskType', 'string', CreoleTypes::VARCHAR, true, 20);

        $tMap->addColumn('ACT_IMPLEMENTATION', 'ActImplementation', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('ACT_INSTANTIATE', 'ActInstantiate', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_SCRIPT_TYPE', 'ActScriptType', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ACT_SCRIPT', 'ActScript', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('ACT_LOOP_TYPE', 'ActLoopType', 'string', CreoleTypes::VARCHAR, true, 20);

        $tMap->addColumn('ACT_TEST_BEFORE', 'ActTestBefore', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_LOOP_MAXIMUM', 'ActLoopMaximum', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('ACT_LOOP_CONDITION', 'ActLoopCondition', 'string', CreoleTypes::VARCHAR, false, 100);

        $tMap->addColumn('ACT_LOOP_CARDINALITY', 'ActLoopCardinality', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('ACT_LOOP_BEHAVIOR', 'ActLoopBehavior', 'string', CreoleTypes::VARCHAR, false, 20);

        $tMap->addColumn('ACT_IS_ADHOC', 'ActIsAdhoc', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_IS_COLLAPSED', 'ActIsCollapsed', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_COMPLETION_CONDITION', 'ActCompletionCondition', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ACT_ORDERING', 'ActOrdering', 'string', CreoleTypes::VARCHAR, false, 20);

        $tMap->addColumn('ACT_CANCEL_REMAINING_INSTANCES', 'ActCancelRemainingInstances', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_PROTOCOL', 'ActProtocol', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ACT_METHOD', 'ActMethod', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ACT_IS_GLOBAL', 'ActIsGlobal', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('ACT_REFERER', 'ActReferer', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('ACT_DEFAULT_FLOW', 'ActDefaultFlow', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('ACT_MASTER_DIAGRAM', 'ActMasterDiagram', 'string', CreoleTypes::VARCHAR, false, 32);

    } // doBuild()

} // BpmnActivityMapBuilder
