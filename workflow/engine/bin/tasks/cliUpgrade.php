<?php

G::LoadClass("system");
G::LoadClass("wsTools");

pake_task("upgrade");

function run_upgrade($command, $args) {
  logging("UPGRADE", PROCESSMAKER_PATH . "upgrade.log");
  logging("Checking files integrity...\n");
  $checksum = System::verifyChecksum();
  if ($checksum === false) {
    logging(error("checksum.txt not found, integrity check is not possible") . "\n");
    if (!question("Integrity check failed, do you want to continue the upgrade?")) {
      logging("Upgrade failed\n");
      die();
    }
  } else {
    if (!empty($checksum['missing'])) {
      logging(error("The following files were not found in the installation:")."\n");
      foreach($checksum['missing'] as $missing) {
        logging(" $missing\n");
      }
    }
    if (!empty($checksum['diff'])) {
      logging(error("The following files have modifications:")."\n");
      foreach($checksum['diff'] as $diff) {
        logging(" $diff\n");
      }
    }
    if (!(empty($checksum['missing']) || empty($checksum['diff']))) {
      if (!question("Integrity check failed, do you want to continue the upgrade?")) {
        logging("Upgrade failed\n");
        die();
      }
    }
  }
  logging("Clearing cache...\n");
  if(defined('PATH_C'))
    G::rm_dir(PATH_C);
  $workspaces = get_workspaces_from_args($args);
  $count = count($workspaces);
  $first = true;
  foreach ($workspaces as $index => $workspace) {
    try {
      logging("Upgrading workspaces ($index/$count): " . info($workspace->name) . "\n");
      $workspace->upgrade($first);
      $workspace->close();
      $first = false;
    } catch (Exception $e) {
      logging("Errors upgrading workspace " . info($workspace->name) . ": " . error($e->getMessage()) . "\n");
    }
  }
  logging("Upgrade successful\n");
}

?>
