<?php

/**
 * Utility functions to manage a workspace.
 *
 * @author Alexandre Rosenfeld
 */

G::LoadSystem('dbMaintenance');

class workspaceTools {
  var $name = NULL;
  var $path = NULL;
  var $db = NULL;
  var $dbPath = NULL;
  var $dbInfo = NULL;
  var $dbInfoRegExp = "/( *define *\( *'(?P<key>.*?)' *, *\n* *')(?P<value>.*?)(' *\) *;.*)/";
  var $initPropel = false;
  var $initPropelRoot = false;

  /**
   * Create a workspace tools object. Note that workspace might not exist when
   * this object is created, however most methods requires that a workspace with
   * this name does exists.
   *
   * @author Alexandre Rosenfeld <alexandre@colosa.com>
   * @access public
   * @param  string $workspaceName name of the workspace
   * @return void
   */
  function  __construct($workspaceName)  {
    $this->name = $workspaceName;
    $this->path = PATH_DB . $this->name;
    $this->dbPath = $this->path . '/db.php';
    if ($this->workspaceExists())
      $this->getDBInfo ();
  }

  public function workspaceExists() {
    return (file_exists($this->path) && file_exists($this->dbPath));
  }

  public function upgrade($first = false) {
    logging("> Updating database...\n");
    $this->upgradeDatabase();
    logging("> Updating translations...\n");
    $this->upgradeTranslation($first);
    logging("> Updating cache view...\n");
    $this->upgradeCacheView();
  }

  public function getDBInfo() {
    if (!$this->workspaceExists())
      throw new Exception("Could not get db.php in workspace " . $this->name);
    if (isset($this->dbInfo))
      return $this->dbInfo;
    $sDbFile = file_get_contents($this->dbPath);
    /* This regular expression will match any "define ('<key>', '<value>');"
     * with any combination of whitespace between words.
     * Each match will have these groups:
     * ((define('(<key>)2', ')1 (<value>)3 (');)4 )0
     */
    preg_match_all($this->dbInfoRegExp, $sDbFile, $matches, PREG_SET_ORDER);
    $values = array();
    foreach ($matches as $match) {
      $values[$match['key']] = $match['value'];
    }
    $this->dbAdapter = $values["DB_ADAPTER"];
    $this->dbName = $values["DB_NAME"];
    $this->dbHost = $values["DB_HOST"];
    $this->dbUser = $values["DB_USER"];
    $this->dbPass = $values["DB_PASS"];
    return $this->dbInfo = $values;
  }

  public function getDBCredentials($dbName) {
    $prefixes = array(
        "wf" => "",
        "rp" => "REPORT_",
        "rb" => "RBAC_"
    );
    $prefix = $prefixes[$dbName];
    $dbInfo = $this->getDBInfo();
    return array(
      'adapter' => $dbInfo["DB_ADAPTER"],
      'name' => $dbInfo["DB_".$prefix."NAME"],
      'host' => $dbInfo["DB_".$prefix."HOST"],
      'user' => $dbInfo["DB_".$prefix."USER"],
      'pass' => $dbInfo["DB_".$prefix."PASS"],
      'dsn'  => sprintf("%s://%s:%s@%s/%s?encoding=utf8", $dbInfo['DB_ADAPTER'] ,
        $dbInfo["DB_".$prefix."USER"], $dbInfo["DB_".$prefix."PASS"],
        $dbInfo["DB_".$prefix."HOST"], $dbInfo["DB_".$prefix."NAME"])
    );
  }

  private function initPropel($root = false) {
    if (($this->initPropel && !$root) || ($this->initPropelRoot && $root))
      return;
    $wfDetails = $this->getDBCredentials("wf");
    $rbDetails = $this->getDBCredentials("rb");
    $rpDetails = $this->getDBCredentials("rp");

    $config = array(
        'datasources' => array(
            'workflow' => array(
                'connection' => $wfDetails["dsn"],
                'adapter'    => $wfDetails["adapter"]
            ),
            'rbac' => array(
                'connection' => $rbDetails["dsn"],
                'adapter'    => $rbDetails["adapter"]
            ),
            'rp' => array(
                'connection' => $rpDetails["dsn"],
                'adapter'    => $rpDetails["adapter"]
            )
        )
    );

    if ($root) {
      $dbHash = @explode(SYSTEM_HASH, G::decrypt(HASH_INSTALLATION, SYSTEM_HASH));

      $dbInfo = $this->getDBInfo();
      $host = $dbHash[0];
      $user = $dbHash[1];
      $pass = $dbHash[2];
      $dbName = $dbInfo["DB_NAME"];

      $rootConfig = array(
          'datasources' => array(
              'root' => array(
                  'connection' => "mysql://$user:$pass@$host/$dbName?encoding=utf8",
                  'adapter' => "mysql"
              )
          )
      );

      $config["datasources"] = array_merge($config["datasources"], $rootConfig["datasources"]);

      $this->initPropelRoot = true;
    }

    $this->initPropel = true;

    require_once ( "propel/Propel.php" );
    require_once ( "creole/Creole.php" );

    Propel::initConfiguration($config);
  }

  private function closePropel() {
    Propel::close();
    $this->initPropel = false;
    $this->initPropelRoot = false;
  }

  public function upgradeTranslation($updateXml = true) {
    $this->initPropel(true);
    G::LoadClass('languages');
    G::LoadThirdParty('pear/json', 'class.json');
    $languages = new languages();
    foreach (System::listPoFiles() as $poFile) {
      logging("Updating language ".basename($poFile)."\n");
      $languages->importLanguage($poFile, $updateXml);
    }
  }

  private function getDatabase() {
    if (isset($this->db) && $this->db->isConnected())
      return $this->db;
    G::LoadSystem( 'database_' . strtolower($this->dbAdapter));
    $this->db = new database($this->dbAdapter, $this->dbHost, $this->dbUser,
      $this->dbPass, $this->dbName);
    if ( !$this->db->isConnected() ) {
      $this->db->logQuery ('No available connection to database!');
      throw new Exception("Could not connect to database");
    }
    return $this->db;
  }

  private function closeDatabase() {
    if (!isset($this->db))
      return;
    $this->db->close();
    $this->db = NULL;
  }

  public function close() {
    $this->closePropel();
    $this->closeDatabase();
  }

  public function getSchema() {
    $oDataBase = $this->getDatabase();

    $aOldSchema = array();

    try {
      $oDataBase->iFetchType = MYSQL_NUM;
      $oDataset1 = $oDataBase->executeQuery($oDataBase->generateShowTablesSQL());
    } catch ( Exception $e ) {
      $oDataBase->logQuery ( $e->getmessage() );
      return NULL;
    }

    //going thru all tables in current WF_ database
    while ($aRow1 = $oDataBase->getRegistry( $oDataset1) ) {
      $aPrimaryKeys = array();
      $sTable = strtoupper($aRow1[0]);

      //get description of each table, ( column and primary keys )
      //$oDataset2 = $oDataBase->executeQuery( $oDataBase->generateDescTableSQL($aRow1[0]) );
      $oDataset2 = $oDataBase->executeQuery( $oDataBase->generateDescTableSQL($sTable ) );
      $aOldSchema[ $sTable ] = array();
      $oDataBase->iFetchType = MYSQL_ASSOC;
      while ($aRow2 = $oDataBase->getRegistry($oDataset2)) {
        $aOldSchema[$sTable][$aRow2['Field']]['Field']   = $aRow2['Field'];
        $aOldSchema[$sTable][$aRow2['Field']]['Type']    = $aRow2['Type'];
        $aOldSchema[$sTable][$aRow2['Field']]['Null']    = $aRow2['Null'];
        $aOldSchema[$sTable][$aRow2['Field']]['Default'] = $aRow2['Default'];
      }

      //get indexes of each table  SHOW INDEX FROM `ADDITIONAL_TABLES`;   -- WHERE Key_name <> 'PRIMARY'
      $oDataset2 = $oDataBase->executeQuery($oDataBase->generateTableIndexSQL($aRow1[0]));
      $oDataBase->iFetchType = MYSQL_ASSOC;
      while ($aRow2 = $oDataBase->getRegistry($oDataset2)) {
        if ( !isset($aOldSchema[$sTable]['INDEXES']) ) {
          $aOldSchema[$sTable]['INDEXES'] = array();
        }
        if (!isset($aOldSchema[$sTable]['INDEXES'][$aRow2['Key_name']] ) )  {
          $aOldSchema[$sTable]['INDEXES'][$aRow2['Key_name']] = array();
        }
        $aOldSchema[$sTable]['INDEXES'][$aRow2['Key_name']][] = $aRow2['Column_name'];
      }

      $oDataBase->iFetchType = MYSQL_NUM; //this line is neccesary because the next fetch needs to be with MYSQL_NUM
    }
    //finally return the array with old schema obtained from the Database
    if ( count($aOldSchema) == 0 ) $aOldSchema = null;
    return $aOldSchema;
  }

  public function upgradeCacheView($checkOnly = false, $lang = "en") {
    $this->initPropel(true);

    require_once('classes/model/AppCacheView.php');

    //check the language, if no info in config about language, the default is 'en'
    G::loadClass('configuration');
    $oConf = new Configurations;
    $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE','','','','');
    $appCacheViewEngine = $oConf->aConfig;

    //setup the appcacheview object, and the path for the sql files
    $appCache = new AppCacheView();
    $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );

    $userGrants = $appCache->checkGrantsForUser( false );

    $currentUser        = $res['user'];
    $currentUserIsSuper = $res['super'];

    //if user does not have the SUPER privilege we need to use the root user and grant the SUPER priv. to normal user.
    if (!$currentUserIsSuper && !$checkOnly) {
      $res = $appCache->checkGrantsForUser( true );
      $res = $appCache->setSuperForUser( $currentUser );
      $currentUserIsSuper = true;
    }

    logging("Creating table");
    //now check if table APPCACHEVIEW exists, and it have correct number of fields, etc.
    if (!$checkOnly) {
      $res = $appCache->checkAppCacheView();
    }

    logging(", triggers");
    //now check if we have the triggers installed
    $triggers = array();
    $triggers[] = $appCache->triggerAppDelegationInsert($lang, $checkOnly);
    $triggers[] = $appCache->triggerAppDelegationUpdate($lang, $checkOnly);
    $triggers[] = $appCache->triggerApplicationUpdate($lang, $checkOnly);
    $triggers[] = $appCache->triggerApplicationDelete($lang, $checkOnly);

    if (!$checkOnly) {
      logging(", filling cache view");
      //build using the method in AppCacheView Class
      $res = $appCache->fillAppCacheView($lang);
      logging(".");
    }
    logging("\n");
    //set status in config table
    $confParams = Array(
      'LANG' => $lang,
      'STATUS'=> 'active'
    );        
    $oConf->aConfig = $confParams;
    $oConf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');

    // removing casesList configuration records. TODO: removing these lines that resets all the configurations records
    $oCriteria = new Criteria();
    $oCriteria->add(ConfigurationPeer::CFG_UID,'casesList');
    ConfigurationPeer::doDelete($oCriteria);
    // end of reset 
  }

  public function upgradePluginsDatabase() {
    foreach (System::getPlugins() as $pluginName) {
      $pluginSchema = System::getPluginSchema($pluginName);
      if ($pluginSchema !== false) {
        logging("Updating plugin " . info($pluginName) . "\n");
        $this->upgradeSchema($pluginSchema);
      }
    }
  }

  public function upgradeDatabase($checkOnly = false) {
    $systemSchema = System::getSystemSchema();
    return $this->upgradeSchema($systemSchema);
  }

  public function upgradeSchema($schema, $checkOnly = false) {
    $dbInfo = $this->getDBInfo();

    if (strcmp($dbInfo["DB_ADAPTER"], "mysql") != 0) {
      throw new Exception("Only MySQL is supported");
    }

    $workspaceSchema = $this->getSchema();
    $changes = System::compareSchema($workspaceSchema, $schema);
    $changed = (count($changes['tablesToAdd']) > 0 ||
                count($changes['tablesToAlter']) > 0 ||
                count($changes['tablesWithNewIndex']) > 0 ||
                count($changes['tablesToAlterIndex']) > 0);
    if ($checkOnly || (!$changed)) {
      if ($changed)
        return $changes;
      else
        return $changed;
    }

    $oDataBase = $this->getDatabase();
    $oDataBase->iFetchType = MYSQL_NUM;

    $oDataBase->logQuery ( count ($changes ) );

    logging( "" . count($changes['tablesToAdd']) . " tables to add");
    foreach ($changes['tablesToAdd'] as $sTable => $aColumns) {
      $oDataBase->executeQuery($oDataBase->generateCreateTableSQL($sTable, $aColumns));
      if (isset($changes['tablesToAdd'][$sTable]['INDEXES'])) {
        foreach ($changes['tablesToAdd'][$sTable]['INDEXES'] as $indexName => $aIndex) {
          $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $indexName, $aIndex ) );
        }
      }
    }

    logging(", " . count($changes['tablesToAlter']) . " tables to alter");
    foreach ($changes['tablesToAlter'] as $sTable => $aActions) {
      foreach ($aActions as $sAction => $aAction) {
        foreach ($aAction as $sColumn => $vData) {
          switch ($sAction) {
            case 'DROP':
              $oDataBase->executeQuery($oDataBase->generateDropColumnSQL($sTable, $vData));
            break;
            case 'ADD':
              $oDataBase->executeQuery($oDataBase->generateAddColumnSQL($sTable, $sColumn, $vData));
            break;
            case 'CHANGE':
              $oDataBase->executeQuery($oDataBase->generateChangeColumnSQL($sTable, $sColumn, $vData));
            break;
          }
        }
      }
    }

    logging(", " . count($changes['tablesWithNewIndex']) . " indexes to add");
    foreach ($changes['tablesWithNewIndex'] as $sTable => $aIndexes) {
      foreach ($aIndexes as $sIndexName => $aIndexFields ) {
        $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $sIndexName, $aIndexFields ));
      }
    }

    logging(", " . count($changes['tablesWithNewIndex']) . " indexes to alter");
    foreach ($changes['tablesToAlterIndex'] as $sTable => $aIndexes) {
      foreach ($aIndexes as $sIndexName => $aIndexFields ) {
        $oDataBase->executeQuery($oDataBase->generateDropKeySQL($sTable, $sIndexName ));
        $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $sIndexName, $aIndexFields ));
      }
    }
    logging("\n");
    $this->closeDatabase();
    return true;
  }

  public function getMetadata() {
    $Fields = array_merge(System::getSysInfo(), $this->getDBInfo());
    $Fields['WORKSPACE_NAME'] = $this->name;

    if(isset($this->dbHost)) {

      //TODO: This code stopped working with the refactoring
      //require_once ("propel/Propel.php");
      //G::LoadClass('dbConnections');
      //$dbConns = new dbConnections('');
      //$availdb = '';
      //foreach( $dbConns->getDbServicesAvailables() as $key => $val ) {
      //if(!empty($availdb))
      //  $availdb .= ', ';
      //  $availdb .= $val['name'];
      //}

      G::LoadClass('net');
      $dbNetView = new NET($this->dbHost);
      $dbNetView->loginDbServer($this->dbUser, $this->dbPass);
      try {
        $sMySQLVersion = $dbNetView->getDbServerVersion('mysql');
      } catch( Exception $oException ) {
        $sMySQLVersion = 'Unknown';
      }

      $Fields['DATABASE'] = $dbNetView->dbName($this->dbAdapter) . ' (Version ' . $sMySQLVersion . ')';
      $Fields['DATABASE_SERVER'] = $this->dbHost;
      $Fields['DATABASE_NAME'] = $this->dbName;
      $Fields['AVAILABLE_DB'] = "Not defined";
      //$Fields['AVAILABLE_DB'] = $availdb;
    } else {
      $Fields['DATABASE'] = "Not defined";
      $Fields['DATABASE_SERVER'] = "Not defined";
      $Fields['DATABASE_NAME'] = "Not defined";
      $Fields['AVAILABLE_DB'] = "Not defined";
    }

    return $Fields;
  }

 /** 
  * Print the system information gathered from getSysInfo
  *
  * @return
  */ 
  public static function printSysInfo() {
    $fields = System::getSysInfo();

    $info = array(
        'ProcessMaker Version' => $fields['PM_VERSION'],
        'System'               => $fields['SYSTEM'],
        'PHP Version'          => $fields['PHP'],
        'Server Address'       => $fields['SERVER_ADDR'],
        'Client IP Address'    => $fields['IP'],
        'Plugins'              => (count($fields['PLUGINS_LIST']) > 0) ? $fields['PLUGINS_LIST'][0] : 'None'
    );

    foreach( $fields['PLUGINS_LIST'] as $k => $v ) {
      if ($k == 0)
        continue;
      $info[] = $v;
    }

    foreach ($info as $k => $v) {
      if (is_numeric($k)) $k = "";
      logging(sprintf("%20s %s\n", $k, pakeColor::colorize($v, 'INFO')));
    }
  }

  public function printMetadata($printSysInfo = true) {
    $fields = $this->getMetadata();

    if ($printSysInfo) {
      workspaceTools::printSysInfo ();
      logging("\n");
    }

    $wfDsn = $fields['DB_ADAPTER'] . '://' . $fields['DB_USER'] . ':' . $fields['DB_PASS'] . '@' . $fields['DB_HOST'] . '/' . $fields['DB_NAME'];
    $rbDsn = $fields['DB_ADAPTER'] . '://' . $fields['DB_RBAC_USER'] . ':' . $fields['DB_RBAC_PASS'] . '@' . $fields['DB_RBAC_HOST'] . '/' . $fields['DB_RBAC_NAME'];
    $rpDsn = $fields['DB_ADAPTER'] . '://' . $fields['DB_REPORT_USER'] . ':' . $fields['DB_REPORT_PASS'] . '@' . $fields['DB_REPORT_HOST'] . '/' . $fields['DB_REPORT_NAME'];

    $info = array(
        'Workspace Name'       => $fields['WORKSPACE_NAME'],
        'Available Databases'  => $fields['AVAILABLE_DB'],
        'Workflow Database'    => sprintf("%s://%s:%s@%s/%s", $fields['DB_ADAPTER'] , $fields['DB_USER'], $fields['DB_PASS'], $fields['DB_HOST'], $fields['DB_NAME']),
        'RBAC Database'        => sprintf("%s://%s:%s@%s/%s", $fields['DB_ADAPTER'] , $fields['DB_RBAC_USER'], $fields['DB_RBAC_PASS'], $fields['DB_RBAC_HOST'], $fields['DB_RBAC_NAME']),
        'Report Database'      => sprintf("%s://%s:%s@%s/%s", $fields['DB_ADAPTER'] , $fields['DB_REPORT_USER'], $fields['DB_REPORT_PASS'], $fields['DB_REPORT_HOST'], $fields['DB_REPORT_NAME']),
        'MySql Version'        => $fields['DATABASE'],
    );

    foreach ($info as $k => $v) {
      if (is_numeric($k)) $k = "";
      logging(sprintf("%20s %s\n", $k, pakeColor::colorize($v, 'INFO')));
    }
  }

  public function exportDatabase($path) {
    $dbInfo = $this->getDBInfo();
    $databases = array("wf", "rp", "rb");
    foreach ($databases as $db) {
      $dbInfo = $this->getDBCredentials($db);
      $oDbMaintainer = new DataBaseMaintenance($dbInfo["host"], $dbInfo["user"],
        $dbInfo["pass"]);
      $oDbMaintainer->connect($dbInfo["name"]);
      $oDbMaintainer->setTempDir($path . "/" . $dbInfo["name"] . "/");
      $oDbMaintainer->backupDataBaseSchema($oDbMaintainer->getTempDir() . $dbInfo["name"] . ".sql");
      $oDbMaintainer->backupSqlData();
    }
  }

  public function addToBackup($backup, $filename, $root) {
    if (is_file($filename)) {
      $backup->addModify($filename, "", $root);
    } else {
      foreach (glob($filename . "/*") as $item) {
        $this->addToBackup($backup, $item, $root);
      }
    }
  }

  static public function createBackup($filename, $compress = true) {
    G::LoadThirdParty('pear/Archive', 'Tar');
    $backup = new Archive_Tar($filename);
    return $backup;
  }

  public function backup($filename, $compress = true) {
    if (is_string($filename))
      $backup = $this->createBackup($filename);
    else
      $backup = $filename;
    //Get a temporary directory for database backup
    $tempDirectory = tempnam(__FILE__, '');
    if (file_exists($tempDirectory)) {
      unlink($tempDirectory);
    }
    mkdir($tempDirectory);
    //$this->exportDatabase($tempDirectory);
    //print_r(info("Database: $tempDirectory\n"));
    $metaFilename = "$tempDirectory/{$this->name}.meta";
    file_put_contents($metaFilename,
      str_replace(array(",", "{", "}"), array(",\n  ", "{\n  ", "\n}\n"),
                  G::json_encode($this->getMetadata())));
    $this->addToBackup($backup, $tempDirectory, $tempDirectory);
    $this->addToBackup($backup, $this->path, $this->path);
    //print_r(file_get_contents($metaFilename));
    //print_r(G::json_decode(file_get_contents($metaFilename)));
    //$this->addToBackup($backup, $filename, $root);
  }

}
?>
