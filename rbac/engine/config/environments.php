<?php
/**
 * environments.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
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
   
  $G_ENVIRONMENTS = array (
    G_PRO_ENV => array ( 
      'dbfile' => PATH_DB . 'production' . PATH_SEP . 'db.php' ,
      'cache' => 1,
      'debug' => 0,
    ) ,
    G_DEV_ENV => array ( 
      'dbfile' => PATH_DB . 'os' . PATH_SEP . 'db.php', 
      'datasource' => 'workflow',
      'cache' => 0,
      'debug' => 1,
    ) ,
    G_TEST_ENV => array ( 
      'dbfile' => PATH_DB . 'test' . PATH_SEP . 'db.php' ,
      'cache' => 0,
      'debug' => 0,
    ) ,
  );

?>
