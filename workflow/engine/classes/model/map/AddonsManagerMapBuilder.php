<?php
require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';

/**
 * This class adds structure of 'ADDONS_MANAGER' table to 'workflow' DatabaseMap object.
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
class AddonsManagerMapBuilder
{
    /**
     * The (dot-path) name of this class
    */
    const CLASS_NAME = 'classes.model.map.AddonsManagerMapBuilder';
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

        $tMap = $this->dbMap->addTable('ADDONS_MANAGER');

        $tMap->setPhpName('AddonsManager');

        $tMap->setUseIdGenerator(false);

        $tMap->addPrimaryKey('ADDON_ID', 'AddonId', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addPrimaryKey('STORE_ID', 'StoreId', 'string', CreoleTypes::VARCHAR, true, 32);

        $tMap->addColumn('ADDON_NAME', 'AddonName', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_NICK', 'AddonNick', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_DOWNLOAD_FILENAME', 'AddonDownloadFilename', 'string', CreoleTypes::VARCHAR, false, 1024);

        $tMap->addColumn('ADDON_DESCRIPTION', 'AddonDescription', 'string', CreoleTypes::VARCHAR, false, 2048);

        $tMap->addColumn('ADDON_STATE', 'AddonState', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_STATE_CHANGED', 'AddonStateChanged', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('ADDON_STATUS', 'AddonStatus', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_VERSION', 'AddonVersion', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_TYPE', 'AddonType', 'string', CreoleTypes::VARCHAR, true, 255);

        $tMap->addColumn('ADDON_PUBLISHER', 'AddonPublisher', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ADDON_RELEASE_DATE', 'AddonReleaseDate', 'int', CreoleTypes::TIMESTAMP, false, null);

        $tMap->addColumn('ADDON_RELEASE_TYPE', 'AddonReleaseType', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ADDON_RELEASE_NOTES', 'AddonReleaseNotes', 'string', CreoleTypes::VARCHAR, false, 255);

        $tMap->addColumn('ADDON_DOWNLOAD_URL', 'AddonDownloadUrl', 'string', CreoleTypes::VARCHAR, false, 2048);

        $tMap->addColumn('ADDON_DOWNLOAD_PROGRESS', 'AddonDownloadProgress', 'double', CreoleTypes::FLOAT, false, null);

        $tMap->addColumn('ADDON_DOWNLOAD_MD5', 'AddonDownloadMd5', 'string', CreoleTypes::VARCHAR, false, 32);
    }
}

