<?php
/**
 * pakeTest.php
 * @package gulliver.bin.tasks
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.23
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

 

pake_desc('launch unit tests');
pake_task('test-unit', 'project_exists');

pake_desc('launch functional tests for an application');
pake_task('test-functional', 'project_exists');

pake_desc('launch all tests');
pake_task('test-all', 'project_exists');

   /**
   * Function run_test_all
   * access public
   */

function run_test_all($task, $args)
{
  require_once(sfConfig::get('sf_symfony_lib_dir').'/lime/lime.php');

  $h = new lime_harness(new lime_output_color());
  $h->base_dir = sfConfig::get('sf_test_dir');

  // register all tests
  $finder = pakeFinder::type('file')->ignore_version_control()->follow_link()->name('*Test.php');
  $h->register($finder->in($h->base_dir));

  $h->run();
}
   
function run_test_functional($task, $args)
{
  if (!count($args))
  {
    throw new Exception('You must provide the app to test.');
  }

  $app = $args[0];

  if (!is_dir(sfConfig::get('sf_app_dir').DIRECTORY_SEPARATOR.$app))
  {
    throw new Exception(sprintf('The app "%s" does not exist.', $app));
  }

  if (isset($args[1]))
  {
    foreach (array_splice($args, 1) as $path)
    {
      $files = pakeFinder::type('file')->ignore_version_control()->follow_link()->name(basename($path).'Test.php')->in(sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'functional'.DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.dirname($path));
      foreach ($files as $file)
      {
        include($file);
      }
    }
  }
  else
  {
    require_once(sfConfig::get('sf_symfony_lib_dir').'/lime/lime.php');

    $h = new lime_harness(new lime_output_color());
    $h->base_dir = sfConfig::get('sf_test_dir').'/functional/'.$app;

    // register functional tests
    $finder = pakeFinder::type('file')->ignore_version_control()->follow_link()->name('*Test.php');
    $h->register($finder->in($h->base_dir));

    $h->run();
  }
}

function run_test_unit($task, $args)
{
  $environment  = isset ( $arg[1] ) ? $arg[1] : G_TEST_ENV;
  printf("start test in %s environment\n", pakeColor::colorize( $environment, 'INFO'));
  define ( 'G_ENVIRONMENT', $environment );
  
  if (isset($args[0]))
  {
    foreach ($args as $path)
    {
      $pathUnit = PATH_CORE . 'test' . PATH_SEP . 'unit' . PATH_SEP . dirname($path);
      $files = pakeFinder::type('file')->ignore_version_control()->follow_link()->name(basename($path).'Test.php')->in( $pathUnit );
      foreach ($files as $file)
      {
        $fName = str_replace ( PATH_CORE . 'test' . PATH_SEP . 'unit' . PATH_SEP , '', $file );
        printf("\ntesting %s \n", pakeColor::colorize( $fName, 'INFO'));
        include($file);
      }
    }
  }
  else
  {
    
    require_once( PATH_THIRDPARTY . '/lime/lime.php');
    $h = new lime_harness(new lime_output_color());
    $h->base_dir = $pathUnit = PATH_CORE . 'test' . PATH_SEP . 'unit';
//    $h->base_dir = $pathUnit = PATH_CORE . 'test' . PATH_SEP . 'unit' . PATH_SEP . "processmaker";

    // register unit tests
    $finder = pakeFinder::type('file')->ignore_version_control()->follow_link()->name('*Test.php');
    $h->register($finder->in($h->base_dir));

    $h->run();
  }
}
