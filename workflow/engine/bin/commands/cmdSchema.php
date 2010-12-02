<?php

pake_task('schema-check');
pake_task('schema-fix');

G::LoadClass( "wsTools" );

function schemaCommand($command, $args) {
  if (count($args) < 1) {
    $workspaces = workspaceTools::listWorkspaces();
  } else {
    $workspaces = array(new workspaceTools($args[0]));
  }
  $checkOnly = (strcmp($command, "check") == 0);
  foreach ($workspaces as $workspace) {
    if ($checkOnly)
      print_r("Checking ".$workspace->workspaceName."\n");
    else
      print_r("Fixing ".$workspace->workspaceName."\n");
    try {
      $changes = $workspace->repairSchema($checkOnly);
      if ($changes != false) {
        if ($checkOnly) {
          echo "> Schema has changed, run fix to repair\n";
          echo "  Tables to add:    " . count($changes['tablesToAdd'])."\n";
          echo "  Tables to alter:  " . count($changes['tablesToAlter'])."\n";
          echo "  Indexes to add:   " . count($changes['tablesWithNewIndex'])."\n";
          echo "  Indexes to alter: " . count($changes['tablesToAlterIndex'])."\n";
        } else {
          echo "> Schema fixed\n";
        }
      } else {
        echo "> Schema is OK\n";
      }
    } catch (Exception $e) {
      echo "Could not ". $command ." ". $workspace->workspaceName .": ".$e->getMessage() . "\n";
    }
  }
}

function run_schema_fix($task, $args) {
  schemaCommand("fix", $args);
}

function run_schema_check($task, $args) {
  schemaCommand("check", $args);
}

?>