<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'TIMER_EVENT' table to 'workflow' DatabaseMap object.
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
class TimerEventMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.TimerEventMapBuilder';

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

        $tMap = $this->dbMap->addTable('TIMER_EVENT');
        $tMap->setPhpName('TimerEvent');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('TMREVN_UID', 'TmrevnUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('PRJ_UID', 'PrjUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('EVN_UID', 'EvnUid', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('TMREVN_OPTION', 'TmrevnOption', 'string', CreoleTypes::VARCHAR, true, 50);

        $tMap->addColumn('TMREVN_START_DATE', 'TmrevnStartDate', 'int', CreoleTypes::DATE, false, null);

        $tMap->addColumn('TMREVN_END_DATE', 'TmrevnEndDate', 'int', CreoleTypes::DATE, false, null);

        $tMap->addColumn('TMREVN_DAY', 'TmrevnDay', 'string', CreoleTypes::VARCHAR, true, 5);

        $tMap->addColumn('TMREVN_HOUR', 'TmrevnHour', 'string', CreoleTypes::VARCHAR, true, 5);

        $tMap->addColumn('TMREVN_MINUTE', 'TmrevnMinute', 'string', CreoleTypes::VARCHAR, true, 5);

        $tMap->addColumn('TMREVN_CONFIGURATION_DATA', 'TmrevnConfigurationData', 'string', CreoleTypes::LONGVARCHAR, true, null);

        $tMap->addColumn('TMREVN_NEXT_RUN_DATE', 'TmrevnNextRunDate', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('TMREVN_LAST_RUN_DATE', 'TmrevnLastRunDate', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('TMREVN_LAST_EXECUTION_DATE', 'TmrevnLastExecutionDate', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('TMREVN_STATUS', 'TmrevnStatus', 'string', CreoleTypes::VARCHAR, true, 25);

        $tMap->addValidator('TMREVN_OPTION', 'validValues', 'propel.validator.ValidValuesValidator', 'HOURLY|DAILY|MONTHLY|EVERY|ONE-DATE-TIME|WAIT-FOR|WAIT-UNTIL-SPECIFIED-DATE-TIME', 'Please set a valid value for TMREVN_OPTION');

        $tMap->addValidator('TMREVN_STATUS', 'validValues', 'propel.validator.ValidValuesValidator', 'ACTIVE|INACTIVE|PROCESSED', 'Please set a valid value for TMREVN_STATUS');

    } // doBuild()

} // TimerEventMapBuilder
