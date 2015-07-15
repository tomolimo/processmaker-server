<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'OAUTH_CLIENTS' table to 'workflow' DatabaseMap object.
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
class OauthClientsMapBuilder
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'classes.model.map.OauthClientsMapBuilder';

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

        $tMap = $this->dbMap->addTable('OAUTH_CLIENTS');
        $tMap->setPhpName('OauthClients');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('CLIENT_ID', 'ClientId', 'string', CreoleTypes::VARCHAR, true, 80);

        $tMap->addColumn('CLIENT_SECRET', 'ClientSecret', 'string', CreoleTypes::VARCHAR, true, 80);

        $tMap->addColumn('CLIENT_NAME', 'ClientName', 'string', CreoleTypes::VARCHAR, true, 256);

        $tMap->addColumn('CLIENT_DESCRIPTION', 'ClientDescription', 'string', CreoleTypes::VARCHAR, true, 1024);

        $tMap->addColumn('CLIENT_WEBSITE', 'ClientWebsite', 'string', CreoleTypes::VARCHAR, true, 1024);

        $tMap->addColumn('REDIRECT_URI', 'RedirectUri', 'string', CreoleTypes::VARCHAR, true, 2000);

        $tMap->addColumn('USR_UID', 'UsrUid', 'string', CreoleTypes::VARCHAR, true, 32);

    } // doBuild()

} // OauthClientsMapBuilder
