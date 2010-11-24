<?php

pake_task('drafts');

function delete_app_from_table($con, $table_name, $app_uid) {
  $stmt = $con->createStatement();
  $sql = "DELETE FROM " . $table_name . " WHERE APP_UID='" . $app_uid . "'";
  $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
}

function run_drafts($task, $args)
{
  if (count($args) < 1)
    throw new Exception("Command not specified");
  if (strcmp($args[0], 'clean') == 0) {
    echo "Cleaning drafts\n";
    
    if (count($args) < 2)
      throw new Exception ("Please specify a workspace name");
    $workspace = $args[1];

    if (!file_exists(PATH_DB . $workspace . '/db.php')) {
      throw new Exception('Could not find workspace ' . $workspace);
    }
    /* Load the configuration from the workspace */
    require_once( PATH_DB . $workspace . '/db.php' );

    require_once( PATH_THIRDPARTY . 'propel/Propel.php');

    PROPEL::Init ( PATH_METHODS.'dbConnections/rootDbConnections.php' );
    $con = Propel::getConnection("root");

    $stmt = $con->createStatement();

    /* Search for all the draft cases */
    $sql = "SELECT APP_UID FROM APPLICATION WHERE APP_STATUS='DRAFT'";
    $app_list = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);

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

    echo "Found " . $app_list->getRecordCount() . " cases to remove";
    foreach ($app_list as $row) {
      echo ".";
      foreach ($tables as $table) {
        delete_app_from_table($con, $table, $row['APP_UID']);
      }
    }
    echo "\n";
  }
}
?>
