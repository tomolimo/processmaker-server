<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'MESSAGE_TYPE_VARIABLE' table to 'workflow' DatabaseMap object.
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
class MessageTypeVariableMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.MessageTypeVariableMapBuilder';

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

        $tMap = $this->dbMap->addTable('MESSAGE_TYPE_VARIABLE');
        $tMap->setPhpName('MessageTypeVariable');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('MSGTV_UID', 'MsgtvUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('MSGT_UID', 'MsgtUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('MSGTV_NAME', 'MsgtvName', 'string', CreoleTypes::VARCHAR, false, 512);

        $tMap->addColumn('MSGTV_DEFAULT_VALUE', 'MsgtvDefaultValue', 'string', CreoleTypes::VARCHAR, false, 512);

    } // doBuild()

} // MessageTypeVariableMapBuilder
