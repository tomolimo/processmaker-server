<?php

pake_task('drafts-clean');

function delete_app_from_table($con, $tableName, $appUid, $col="APP_UID") {
  $stmt = $con->createStatement();
  $sql = "DELETE FROM " . $tableName . " WHERE " . $col . "='" . $appUid . "'";
  $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
}

function run_drafts_clean($task, $args)
{
  echo "Cleaning drafts\n";
    
  if (count($args) < 1)
    throw new Exception ("Please specify a workspace name");
  $workspace = $args[0];

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

  if ($allDrafts)
    echo "Removing all drafts\n";
  else
    echo "Removing drafts older than " . $days . " days\n";

  if (!$allDrafts && (!is_numeric($days) || intval($days) <= 0)) {
    throw new Exception("Days value is not valid: " . $days);
  }

  if (!file_exists(PATH_DB . $workspace . '/db.php')) {
    throw new Exception('Could not find workspace ' . $workspace);
  }
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
