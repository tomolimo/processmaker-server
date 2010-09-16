<?php
/**
 * error401.php
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
 $ERROR_TEXT = "401 Unauthorized   ";
 $ERROR_DESCRIPTION = "
      This server could not verify that
      you are authorized to access. You either supplied the wrong credentials
      (e.g., bad password), or your browser doesn't understand how to supply
      the credentials required. <br />
      <br />
      In case you are allowed to request the document,
      please check your user-id and password and try again<br />
      <br />
  ";

  $fileHeader = PATH_GULLIVER_HOME . 'methods/errors/header.php' ;
  include ( $fileHeader);
?>