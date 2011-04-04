<?php
/**
 * cli.php
 * @package workflow-engine-bin
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
 */

  /* Windows supports both / and \ as path separators, so use the Unix separator
   * for maximum compatibility.
   */
  define('PATH_SEP', '/');

  define('PATH_HOME',     WORKFLOW_PATH );
  define('PATH_TRUNK',    PROCESSMAKER_PATH  );
  define('PATH_OUTTRUNK', realpath(PROCESSMAKER_PATH.'/..') );

  /* Most definitions (including the G class) is done in paths.php
   * This mostly simulates a sysGeneric.php call.
   */
  if (file_exists(PATH_HOME . 'engine/config/paths_installed.php'))
      require_once(PATH_HOME . 'engine/config/paths_installed.php');
  require_once ( PATH_HOME . 'engine/config/paths.php' );

  require_once( PATH_THIRDPARTY . 'pake/pakeFunction.php');
  require_once( PATH_THIRDPARTY . 'pake/pakeGetopt.class.php');
  require_once( PATH_CORE . 'config/environments.php');

  /* Hide notice, otherwise we get a lot of messages */
  error_reporting(E_ALL ^ E_NOTICE);
  ini_set('display_errors', 1);

  // trap -V before pake
  if (in_array('-v', $argv) || in_array('-V', $argv) || in_array('--version', $argv))
  {
    printf("ProcessMaker version %s\n", pakeColor::colorize(trim(file_get_contents( PATH_GULLIVER . 'VERSION')), 'INFO'));
    exit(0);
  }
  // register tasks

  // run task
  $directories = array(PATH_HOME . 'engine/bin/tasks');
  $pluginsDirectories = glob(PATH_PLUGINS . "*");
  foreach ($pluginsDirectories as $dir) {
    if (!is_dir($dir))
      continue;
    if (is_dir("$dir/bin/tasks"))
      $directories[] = "$dir/bin/tasks";
  }

  foreach ($directories as $dir) {
    foreach (glob("$dir/*.php") as $filename) {
      include_once($filename);
    }
  }

  pakeApp::get_instance()->run(null, null, false);

  exit(0);

?>
