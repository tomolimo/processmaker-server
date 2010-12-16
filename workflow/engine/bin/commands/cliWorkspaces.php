<?php

pake_task('info');

pake_task('workspace-upgrade');

pake_task('translation-upgrade');
pake_task('cacheview-upgrade');

pake_task('database-upgrade');
pake_task('database-check');

pake_task('database-export');
pake_task('database-import');

pake_task('drafts-clean');

function run_info($command, $args) {
  $workspaces = get_workspaces_from_args($args, false);
  System::printSysInfo();
  foreach ($workspaces as $workspace) {
    echo "\n";
    $workspace->printMetadata(false);
  }
}

function run_workspace_upgrade($command, $args) {
  $workspaces = get_workspaces_from_args($args);
  foreach ($workspaces as $workspace) {
    try {
      $workspace->upgrade();
    } catch (Exception $e) {
      echo "Errors upgrading workspace " . info($workspace->name) . ": " . error($e->getMessage()) . "\n";
    }
  }
}

function run_translation_upgrade($command, $args) {
  $workspaces = get_workspaces_from_args($args);
  $updateXml = true;
  foreach ($workspaces as $workspace) {
    try {
      echo "Upgrading translation for " . pakeColor::colorize($workspace->name, "INFO") . "\n";
      $workspace->upgradeTranslation($updateXml);
      $updateXml = false;
    } catch (Exception $e) {
      echo "Errors upgrading translation of workspace " . info($workspace->name) . ": " . error($e->getMessage()) . "\n";
    }
  }
}

function run_cacheview_upgrade($command, $args) {
  $workspaces = get_workspaces_from_args($args);
  $updateXml = true;
  foreach ($workspaces as $workspace) {
    try {
      echo "Upgrading cache view for " . pakeColor::colorize($workspace->name, "INFO") . "\n";
      $workspace->upgradeCacheView();
    } catch (Exception $e) {
      echo "Errors upgrading translation of workspace " . info($workspace->name) . ": " . error($e->getMessage()) . "\n";
    }
  }
}

function run_database_export($command, $args) {
  G::LoadSystem('dbMaintenance');
  if (count($args) < 2)
    throw new Exception ("Please provide a workspace name and a directory for export");
  $workspace = new workspaceTools($args[0]);
  $workspace->exportDatabase($args[1]);
}

function run_database_import($command, $args) {
  throw new Exception("Not implemented");
}

function run_database_upgrade($task, $args) {
  database_upgrade("upgrade", $args);
}

function run_database_check($task, $args) {
  database_upgrade("check", $args);
}

function database_upgrade($command, $args) {
  $workspaces = get_workspaces_from_args($args);
  $checkOnly = (strcmp($command, "check") == 0);
  foreach ($workspaces as $workspace) {
    if ($checkOnly)
      print_r("Checking database in ".pakeColor::colorize($workspace->name, "INFO")." ");
    else
      print_r("Upgrading database in ".pakeColor::colorize($workspace->name, "INFO")." ");
    try {
      $changes = $workspace->repairSchema($checkOnly);
      if ($changes != false) {
        if ($checkOnly) {
          echo "> ".pakeColor::colorize("Run upgrade", "INFO")."\n";
          echo "  Tables (add = " . count($changes['tablesToAdd']);
          echo ", alter = " . count($changes['tablesToAlter']) . ") ";
          echo "- Indexes (add = " . count($changes['tablesWithNewIndex'])."";
          echo ", alter = " . count($changes['tablesToAlterIndex']).")\n";
        } else {
          echo "> Schema fixed\n";
        }
      } else {
        echo "> OK\n";
      }
    } catch (Exception $e) {
      echo "> Error: ".error($e->getMessage()) . "\n";
    }
  }
}

function delete_app_from_table($con, $tableName, $appUid, $col="APP_UID") {
  $stmt = $con->createStatement();
  $sql = "DELETE FROM " . $tableName . " WHERE " . $col . "='" . $appUid . "'";
  $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
}

function run_drafts_clean($task, $args) {
  echo "Cleaning drafts\n";
    
  if (count($args) < 1)
    throw new Exception ("Please specify a workspace name");
  $workspace = $args[0];
  
  if (!file_exists(PATH_DB . $workspace . '/db.php')) {
    throw new Exception('Could not find workspace ' . $workspace);
  }

  $allDrafts = false;
  if (count($args) < 2) {
    echo "Cases older them this much days will be deleted (ENTER for all): ";
    $days = rtrim( fgets( STDIN ), "\n" );
    if ($days == "") {
      $allDrafts = true;
    }
  } else {
    $days = $args[1];
    if (strcmp($days, "all") == 0) {
      $allDrafts = true;
    }
  }

  if (!$allDrafts && (!is_numeric($days) || intval($days) <= 0)) {
    throw new Exception("Days value is not valid: " . $days);
  }

  if ($allDrafts)
    echo "Removing all drafts\n";
  else
    echo "Removing drafts older than " . $days . " days\n";

  /* Load the configuration from the workspace */
  require_once( PATH_DB . $workspace . '/db.php' );
  require_once( PATH_THIRDPARTY . 'propel/Propel.php');

  PROPEL::Init ( PATH_METHODS.'dbConnections/rootDbConnections.php' );
  $con = Propel::getConnection("root");

  $stmt = $con->createStatement();

  if (!$allDrafts)
    $dateSql = "AND DATE_SUB(CURDATE(),INTERVAL " . $days . " DAY) >= APP_CREATE_DATE";
  else
    $dateSql = "";
  /* Search for all the draft cases */
  $sql = "SELECT APP_UID FROM APPLICATION WHERE APP_STATUS='DRAFT'" . $dateSql;
  $appRows = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);

  /* Tables to remove the cases from */
  $tables = array(
      "APPLICATION",
      "APP_DELEGATION",
      "APP_CACHE_VIEW",
      "APP_THREAD",
      "APP_DOCUMENT",
      "APP_EVENT",
      "APP_HISTORY",
      "APP_MESSAGE"
  );

  echo "Found " . $appRows->getRecordCount() . " cases to remove";
  foreach ($appRows as $row) {
    echo ".";
    $appUid = $row['APP_UID'];
    foreach ($tables as $table) {
      delete_app_from_table($con, $table, $appUid);
    }
    delete_app_from_table($con, "CONTENT", $appUid, "CON_ID");
    if (file_exists(PATH_DB . $workspace . '/files/'. $appUid)) {
      echo "\nRemoving files from " . $appUid . "\n";
      G::rm_dir(PATH_DB . $workspace . '/files/'. $appUid);
    }
  }
  echo "\n";
}

?>