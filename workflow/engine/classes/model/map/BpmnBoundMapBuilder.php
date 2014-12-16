<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'BPMN_BOUND' table to 'workflow' DatabaseMap object.
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
class BpmnBoundMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.BpmnBoundMapBuilder';

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

        $tMap = $this->dbMap->addTable('BPMN_BOUND');
        $tMap->setPhpName('BpmnBound');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('BOU_UID', 'BouUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addForeignKey('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, 'BPMN_PROJECT', 'PRJ_UID', true, 32);

        $tMap->addForeignKey('DIA_UID', 'DiaUid', 'string', CreoleTypes::VARCHAR, 'BPMN_DIAGRAM', 'DIA_UID', true, 32);

        $tMap->addColumn('ELEMENT_UID', 'ElementUid', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('BOU_ELEMENT', 'BouElement', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('BOU_ELEMENT_TYPE', 'BouElementType', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('BOU_X', 'BouX', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('BOU_Y', 'BouY', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('BOU_WIDTH', 'BouWidth', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('BOU_HEIGHT', 'BouHeight', 'int', CreoleTypes::INTEGER, true, null);

        $tMap->addColumn('BOU_REL_POSITION', 'BouRelPosition', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('BOU_SIZE_IDENTICAL', 'BouSizeIdentical', 'int', CreoleTypes::INTEGER, false, null);

        $tMap->addColumn('BOU_CONTAINER', 'BouContainer', 'string', CreoleTypes::VARCHAR, false, 30);

    } // doBuild()

} // BpmnBoundMapBuilder
