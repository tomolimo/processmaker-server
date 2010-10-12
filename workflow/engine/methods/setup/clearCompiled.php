<?php
/**
 * class.g.php
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

/**
 * @author Erik A. O. <erik@colosa.com>
 * @date Oct 1th, 2010
 */

try{
  if( isset($_GET['result']) && $_GET['result'] == 'done') {
    echo "<script>parent.location.href='setup'</script>";
  } else {
    if( defined('PATH_C') ){
      G::rm_dir(PATH_C);
      G::SendTemporalMessage(G::LoadTranslation('ID_CACHE_DELETED_SUCCESS'), 'tmp-info', 'string');
      G::header('location: clearCompiled?result=done');
    }
  }
} catch(Exception $e){
  $errorMsg = $e->getMessage();
  if( strpos($errorMsg, "couldn't be deleted") !== false ){
    $errorMsg = G::LoadTranslation('ID_CACHE_DIR_ISNOT_WRITABLE') . ' ('.PATH_C.')';
  }
  G::SendTemporalMessage($errorMsg, 'error', 'string', null, '100%');
  G::header('location: clearCompiled?result=done');
}