<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'PMOAUTH_USER_ACCESS_TOKENS' table to 'workflow' DatabaseMap object.
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
class PmoauthUserAccessTokensMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.PmoauthUserAccessTokensMapBuilder';

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

        $tMap = $this->dbMap->addTable('PMOAUTH_USER_ACCESS_TOKENS');
        $tMap->setPhpName('PmoauthUserAccessTokens');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ACCESS_TOKEN', 'AccessToken', 'string', CreoleTypes::VARCHAR, true, 40);

        $tMap->addColumn('REFRESH_TOKEN', 'RefreshToken', 'string', CreoleTypes::VARCHAR, true, 40);

        $tMap->addColumn('USER_ID', 'UserId', 'string', CreoleTypes::VARCHAR, false, 32);

        $tMap->addColumn('SESSION_ID', 'SessionId', 'string', CreoleTypes::VARCHAR, true, 64);

        $tMap->addColumn('SESSION_NAME', 'SessionName', 'string', CreoleTypes::VARCHAR, true, 64);

    } // doBuild()

} // PmoauthUserAccessTokensMapBuilder
