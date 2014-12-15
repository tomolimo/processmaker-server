<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'BPMN_EVENT' table to 'workflow' DatabaseMap object.
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
class BpmnEventMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.BpmnEventMapBuilder';

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

        $tMap = $this->dbMap->addTable('BPMN_EVENT');
        $tMap->setPhpName('BpmnEvent');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('EVN_UID', 'EvnUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addForeignKey('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROJECT', 'PRJ_UID', true, 32);

        $tMap->addForeignKey('PRO_UID', 'ProUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROCESS', 'PRO_UID', false, 32);

        $tMap->addColumn('EVN_NAME', 'EvnName', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_TYPE', 'EvnType', 'string', CreoleTypes::VARCHAR, true, 30);

        $tMap->addColumn('EVN_MARKER', 'EvnMarker', 'string', CreoleTypes::VARCHAR, true, 30);

        $tMap->addColumn('EVN_IS_INTERRUPTING', 'EvnIsInterrupting', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('EVN_ATTACHED_TO', 'EvnAttachedTo', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('EVN_CANCEL_ACTIVITY', 'EvnCancelActivity', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('EVN_ACTIVITY_REF', 'EvnActivityRef', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('EVN_WAIT_FOR_COMPLETION', 'EvnWaitForCompletion', 'int', CreoleTypes::TINYINT, false, null);

        $tMap->addColumn('EVN_ERROR_NAME', 'EvnErrorName', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_ERROR_CODE', 'EvnErrorCode', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_ESCALATION_NAME', 'EvnEscalationName', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_ESCALATION_CODE', 'EvnEscalationCode', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_CONDITION', 'EvnCondition', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_MESSAGE', 'EvnMessage', 'string', CreoleTypes::LONGVARCHAR, false, null);

        $tMap->addColumn('EVN_OPERATION_NAME', 'EvnOperationName', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_OPERATION_IMPLEMENTATION_REF', 'EvnOperationImplementationRef', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_TIME_DATE', 'EvnTimeDate', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_TIME_CYCLE', 'EvnTimeCycle', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_TIME_DURATION', 'EvnTimeDuration', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('EVN_BEHAVIOR', 'EvnBehavior', 'string', CreoleTypes::VARCHAR, true, 20);

    } // doBuild()

} // BpmnEventMapBuilder
