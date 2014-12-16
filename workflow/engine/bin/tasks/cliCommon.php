<?php
/**
 * cliCommon.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2011 Colosa Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 * @author Alexandre Rosenfeld <alexandre@colosa.com>
 * @package workflow-engine-bin-tasks
 */

/* Get the size of the terminal (only works on Linux, on Windows it's always 80) */
preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);
if(sizeof($output) == 3 && isset($output[2]) && isset($output[2][0])) {
  define("COLUMNS", $output[2][0]);
} else {
  define("COLUMNS", 80);
}

/**
 * Returns workspace objects from an array of workspace names.
 *
 * @param  array $args an array of workspace names
 * @param  bool $includeAll if true and no workspace is specified in args,
 *                          returns all available workspaces
 * @return array of workspace objects
 */
function get_workspaces_from_args($args, $includeAll = true) {
  $workspaces = array();
  foreach ($args as $arg) {
    $workspaces[] = new workspaceTools($arg);
  }
  if (empty($workspaces) && $includeAll) {
    $workspaces = System::listWorkspaces();
  }
  return $workspaces;
}

?>
