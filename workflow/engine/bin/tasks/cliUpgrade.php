<?php

G::LoadClass("system");
G::LoadClass("wsTools");

pake_task("upgrade");

function run_upgrade($command, $args) {
  echo "Checking files integrity...\n";
  $checksum = System::verifyChecksum();
  if (!empty($checksum['missing'])) {
    echo error("The following files were not found in the installation:")."\n";
    foreach($checksum['missing'] as $missing) {
      echo " $missing\n";
    }
  }
  if (!empty($checksum['diff'])) {
    echo error("The following files have modifications:")."\n";
    foreach($checksum['diff'] as $diff) {
      echo " $diff\n";
    }
  }
  echo "Upgrading workspaces...\n";
  $workspaces = get_workspaces_from_args($args);
  foreach ($workspaces as $workspace) {
    try {
      echo "Upgrading " . info($workspace->name) . "\n";
      $workspace->upgrade();
    } catch (Exception $e) {
      echo "Errors upgrading workspace " . info($workspace->name) . ": " . error($e->getMessage()) . "\n";
    }
  }
}

?>
