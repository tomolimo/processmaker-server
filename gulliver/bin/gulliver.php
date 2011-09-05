<?php
/**
 * gulliver.php
 * @package gulliver.bin.tasks
 * 
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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
 */
 

  if (! PATH_THIRDPARTY ) {
    die("You must launch gulliver command line with the gulliver script\n");
  }
 
  // set magic_quotes_runtime to off
  ini_set('magic_quotes_runtime', 'Off');
  
   /**
   * require_once pakeFunction.php
   */
    require_once( PATH_THIRDPARTY . 'pake' . PATH_SEP . 'pakeFunction.php');
    require_once( PATH_THIRDPARTY . 'pake' . PATH_SEP . 'pakeGetopt.class.php');
    require_once( PATH_CORE . 'config' . PATH_SEP . 'environments.php');

  // trap -V before pake
  if (in_array('-V', $argv) || in_array('--version', $argv))
  {
    printf("Gulliver version %s\n", pakeColor::colorize(trim(file_get_contents( PATH_GULLIVER . 'VERSION')), 'INFO'));
    exit(0);
  }

  if (count($argv) <= 1)
  {
    $argv[] = '-T';
  }


  // register tasks
  $dir = PATH_GULLIVER_HOME . 'bin' . PATH_SEP . 'tasks';
  $tasks = pakeFinder::type('file')->name( 'pake*.php' )->in($dir);

  foreach ($tasks as $task) {
    include_once($task);
  }

  // run task
  pakeApp::get_instance()->run(null, null, false);

  exit(0);
