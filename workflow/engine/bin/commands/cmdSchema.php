<?php
pake_task('schema-check');
pake_task('schema-fix');

function listWorkspaces() {
  $oDirectory = dir(PATH_DB);
  $aWorkspaces = array ();
  while( ($sObject = $oDirectory->read()) ) {
    if( is_dir(PATH_DB . $sObject) && substr($sObject, 0, 1) != '.' && file_exists(PATH_DB . $sObject . PATH_SEP . 'db.php') ) {
      $aWorkspaces[] = $sObject;
    }
  }
  return $aWorkspaces;
}

function getDBInfo($workspace) {
  $dbfile = PATH_DB . $workspace .'/db.php';
  if( file_exists($dbfile) ) {
    $sDbFile = file_get_contents($dbfile);
    /* This regular expression will match any "define ('<key>', '<value>');"
     * with any combination of whitespace between words.
     * Each match will have these groups:
     * ((define('(<key>)2', ')1 (<value>)3 (');)4 )0
     */
    preg_match_all("/( *define *\( *'(?P<key>.*?)' *, *\n* *')(?P<value>.*?)(' *\) *;.*)/",
      $sDbFile, $matches, PREG_SET_ORDER);
    $config = array();
    foreach ($matches as $match) {
      $config[$match['key']] = $match['value'];
    }
    return $config;
  } else {
    throw new Exception("Workspace db.php not found.");
  }
}

function getWorkspaceSchema($workspaceName) {
  $dbInfo = getDBInfo($workspaceName);
  $DB_ADAPTER = $dbInfo["DB_ADAPTER"];
  $DB_HOST = $dbInfo["DB_HOST"];
  $DB_USER = $dbInfo["DB_USER"];
  $DB_PASS = $dbInfo["DB_PASS"];
  $DB_NAME = $dbInfo["DB_NAME"];

  try {
    G::LoadSystem( 'database_' . strtolower($DB_ADAPTER));
  
    $aOldSchema = array();
    $oDataBase = new database($DB_ADAPTER, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    if ( !$oDataBase->isConnected() ) {
      $oDataBase->logQuery ('Not exists an available connection!');
      return NULL;
    }

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

function getFileSchema($sSchemaFile, $dbAdapter) {
  try {
    $aSchema = array();
    $oXml = new DomDocument();
    $oXml->load($sSchemaFile);
    $aTables = $oXml->getElementsByTagName('table');
    foreach ($aTables as $oTable) {
      $aPrimaryKeys = array();
      $sTableName = $oTable->getAttribute('name');
      $aSchema[$sTableName] = array();
      $aColumns = $oTable->getElementsByTagName('column');
      foreach ($aColumns as $oColumn) {
        $sColumName = $oColumn->getAttribute('name');
        $aSchema[$sTableName][$sColumName] = array();
        $aVendors = $oColumn->getElementsByTagName('vendor');
        foreach ($aVendors as $oVendor) {
          if ($oVendor->getAttribute('type') == $dbAdapter) {
            break;
          }
        }
        $aParameters = $oColumn->getElementsByTagName('parameter');
        foreach ($aParameters as $oParameter) {
          $parameterName = ucwords($oParameter->getAttribute('name'));
          if ( $parameterName == 'Key' && strtoupper($oParameter->getAttribute('value')) == 'PRI' ) {
          	$aPrimaryKeys[] = $oColumn->getAttribute('name');
          }

        	if ( in_array ( $parameterName, array('Field','Type','Null','Default') ) ) {
            $aSchema[$sTableName][$sColumName][$parameterName] = $oParameter->getAttribute('value');
          }
        }
      }

      if ( is_array($aPrimaryKeys) && count($aPrimaryKeys) > 0 ) {
        $aSchema[$sTableName]['INDEXES']['PRIMARY'] = $aPrimaryKeys;
      }
      $aIndexes = $oTable->getElementsByTagName('index');
      foreach ($aIndexes as $oIndex) {
      	$aIndex = array();
        $aIndexesColumns = $oIndex->getElementsByTagName('index-column');
        foreach ($aIndexesColumns as $oIndexColumn) {
          $aIndex[] = $oIndexColumn->getAttribute('name');
        }
        $aSchema[$sTableName]['INDEXES'][ $oIndex->getAttribute('name') ] = $aIndex;
      }
    }
    return $aSchema;
  }
  catch (Exception $oError) {
    throw $oError;
  }
}


function obtainChanges($aOldSchema, $aNewSchema) {
  //$aChanges = array('tablesToDelete' => array(), 'tablesToAdd' => array(), 'tablesToAlter' => array());
  //Tables to delete, but this is disabled
  //foreach ($aOldSchema as $sTableName => $aColumns) {
  //  if ( !isset($aNewSchema[$sTableName])) {
  //    if (!in_array($sTableName, array('KT_APPLICATION', 'KT_DOCUMENT', 'KT_PROCESS'))) {
  //      $aChanges['tablesToDelete'][] = $sTableName;
  //    }
  //  }
  //}

  $aChanges = array('tablesToAdd' => array(), 'tablesToAlter' => array(), 'tablesWithNewIndex' => array(), 'tablesToAlterIndex'=> array());

  //new tables  to create and alter
  foreach ($aNewSchema as $sTableName => $aColumns) {
    if (!isset($aOldSchema[$sTableName])) {
      $aChanges['tablesToAdd'][$sTableName] = $aColumns;
    }
    else {
    	//drop old columns
      foreach ($aOldSchema[$sTableName] as $sColumName => $aParameters) {
        if (!isset($aNewSchema[$sTableName][$sColumName])) {
          if (!isset($aChanges['tablesToAlter'][$sTableName])) {
            $aChanges['tablesToAlter'][$sTableName] = array('DROP' => array(), 'ADD' => array(), 'CHANGE' => array());
          }
          $aChanges['tablesToAlter'][$sTableName]['DROP'][$sColumName] = $sColumName;
        }
      }

      //create new columns
      //foreach ($aNewSchema[$sTableName] as $sColumName => $aParameters) {
      foreach ($aColumns as $sColumName => $aParameters) {
        if ($sColumName != 'INDEXES') {
          if (!isset($aOldSchema[$sTableName][$sColumName])) { //this column doesnt exist in oldschema
            if (!isset($aChanges['tablesToAlter'][$sTableName])) {
              $aChanges['tablesToAlter'][$sTableName] = array('DROP' => array(), 'ADD' => array(), 'CHANGE' => array());
            }
            $aChanges['tablesToAlter'][$sTableName]['ADD'][$sColumName] = $aParameters;
          }
          else {  //the column exists
            $newField = $aNewSchema[$sTableName][$sColumName];
            $oldField = $aOldSchema[$sTableName][$sColumName];
            //both are null, no change is required
            if ( !isset($newField['Default']) && !isset($oldField['Default'])) $changeDefaultAttr = false;
            //one of them is null, change IS required
            if ( !isset($newField['Default']) && isset($oldField['Default']) && $oldField['Default']!= '')  $changeDefaultAttr = true;
            if (  isset($newField['Default']) && !isset($oldField['Default'])) $changeDefaultAttr = true;
            //both are defined and they are different.
            if ( isset($newField['Default']) && isset($oldField['Default']) ) {
               if ( $newField['Default'] != $oldField['Default'] )
                 $changeDefaultAttr = true;
               else
                 $changeDefaultAttr = false;
            }
            //special cases
            // BLOB and TEXT columns cannot have DEFAULT values.  http://dev.mysql.com/doc/refman/5.0/en/blob.html
            if ( in_array(strtolower($newField['Type']), array('text','mediumtext') ) )
              $changeDefaultAttr = false;

            //#1067 - Invalid default value for datetime field
            if ( in_array($newField['Type'], array('datetime')) && isset($newField['Default']) && $newField['Default']== '' )
              $changeDefaultAttr = false;

            //#1067 - Invalid default value for int field
            if ( substr($newField['Type'], 0, 3 ) && isset($newField['Default']) && $newField['Default']== '' )
              $changeDefaultAttr = false;

            //if any difference exists, then insert the difference in aChanges
            if ( $newField['Field']   != $oldField['Field'] ||
                 $newField['Type']    != $oldField['Type'] ||
                 $newField['Null']    != $oldField['Null'] ||
                 $changeDefaultAttr ) {
              if (!isset($aChanges['tablesToAlter'][$sTableName])) {
                $aChanges['tablesToAlter'][$sTableName] = array('DROP' => array(), 'ADD' => array(), 'CHANGE' => array());
              }
              $aChanges['tablesToAlter'][$sTableName]['CHANGE'][$sColumName]['Field']   = $newField['Field'];
              $aChanges['tablesToAlter'][$sTableName]['CHANGE'][$sColumName]['Type']    = $newField['Type'];
              $aChanges['tablesToAlter'][$sTableName]['CHANGE'][$sColumName]['Null']    = $newField['Null'];
              if ( isset($newField['Default']) )
                $aChanges['tablesToAlter'][$sTableName]['CHANGE'][$sColumName]['Default'] = $newField['Default'];
              else
                $aChanges['tablesToAlter'][$sTableName]['CHANGE'][$sColumName]['Default'] = null;

            }
          }
        } //only columns, no the indexes column
      }//foreach $aColumns

      //now check the indexes of table
      if ( isset($aNewSchema[$sTableName]['INDEXES']) ) {
        foreach ( $aNewSchema[$sTableName]['INDEXES'] as $indexName => $indexFields ) {
          if (!isset( $aOldSchema[$sTableName]['INDEXES'][$indexName]) ) {
            if (!isset($aChanges['tablesWithNewIndex'][$sTableName])) {
              $aChanges['tablesWithNewIndex'][$sTableName] = array();
            }
            $aChanges['tablesWithNewIndex'][$sTableName][$indexName] = $indexFields;
          }
          else {
            if ( $aOldSchema[$sTableName]['INDEXES'][$indexName] != $indexFields ) {
              if (!isset($aChanges['tablesToAlterIndex'][$sTableName])) {
                $aChanges['tablesToAlterIndex'][$sTableName] = array();
              }
              $aChanges['tablesToAlterIndex'][$sTableName][$indexName] = $indexFields;
            }
          }
        }
      }
    }  //for-else table exists
  }  //for new schema
  return $aChanges;
}

function repairSchema($workspace, $checkOnly = false) {
  $currentSchema = getFileSchema(PATH_TRUNK . "workflow/engine/config/schema.xml", "mysql");
  $workspaceSchema = getWorkspaceSchema($workspace);
  $changes = obtainChanges($workspaceSchema, $currentSchema);
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

  $dbInfo = getDBInfo($workspace);
  $DB_ADAPTER = $dbInfo["DB_ADAPTER"];
  $DB_HOST = $dbInfo["DB_HOST"];
  $DB_USER = $dbInfo["DB_USER"];
  $DB_PASS = $dbInfo["DB_PASS"];
  $DB_NAME = $dbInfo["DB_NAME"];
  
  $oDataBase = new database($DB_ADAPTER, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
  if ( !$oDataBase->isConnected() ) {
    throw new Exception("Could not connect to the database");
  }
  $oDataBase->iFetchType = MYSQL_NUM;

  $oDataBase->logQuery ( count ($changes ) );

  echo "Adding " . count($changes['tablesToAdd']) . " tables\n";
  foreach ($changes['tablesToAdd'] as $sTable => $aColumns) {
    $oDataBase->executeQuery($oDataBase->generateCreateTableSQL($sTable, $aColumns));
    if (isset($changes['tablesToAdd'][$sTable]['INDEXES'])) {
      foreach ($changes['tablesToAdd'][$sTable]['INDEXES'] as $indexName => $aIndex) {
        $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $indexName, $aIndex ) );
      }
    }
  }

  echo "Altering " . count($changes['tablesToAlter']) . " tables\n";
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

  echo "Adding indexes to " . count($changes['tablesWithNewIndex']) . " tables\n";
  foreach ($changes['tablesWithNewIndex'] as $sTable => $aIndexes) {
    foreach ($aIndexes as $sIndexName => $aIndexFields ) {
      $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $sIndexName, $aIndexFields ));
    }
  }

  echo "Altering indexes to " . count($changes['tablesWithNewIndex']) . " tables\n";
  foreach ($changes['tablesToAlterIndex'] as $sTable => $aIndexes) {
    foreach ($aIndexes as $sIndexName => $aIndexFields ) {
      $oDataBase->executeQuery($oDataBase->generateDropKeySQL($sTable, $sIndexName ));
      $oDataBase->executeQuery($oDataBase->generateAddKeysSQL($sTable, $sIndexName, $aIndexFields ));
    }
  }
  $oDataBase->close();
  return true;
}


function run_schema_fix($task, $args) {
  if (count($args) < 1) {
    $workspaces = listWorkspaces();
  } else {
    $workspaces = array($args[0]);
  }
  foreach ($workspaces as $workspace) {
    print_r("Fixing ".$workspace."\n");
    if (repairSchema($workspace) != false) {
      echo "> Fixed schema\n";
    } else {
      echo "> No need to fix\n";
    }
  }
}

function run_schema_check($task, $args) {
  if (count($args) < 1) {
    $workspaces = listWorkspaces();
  } else {
    $workspaces = array($args[0]);
  }
  foreach ($workspaces as $workspace) {
    print_r("Checking ".$workspace."\n");
    $changes = repairSchema($workspace, true);
    if ($changes != false) {
      echo "> Schema has changed, run fix to repair\n";
      echo "  Tables to add:    " . count($changes['tablesToAdd'])."\n";
      echo "  Tables to alter:  " . count($changes['tablesToAlter'])."\n";
      echo "  Indexes to add:   " . count($changes['tablesWithNewIndex'])."\n";
      echo "  Indexes to alter: " . count($changes['tablesToAlterIndex'])."\n";
    } else {
      echo "> Schema is OK\n";
    }
  }
}

?>