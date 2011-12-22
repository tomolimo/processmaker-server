<?php
/**
 * publish.php
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
  global $G_PUBLISH;
  global $G_CONTENT;
  global $G_FORM;
  global $G_TABLE;
  global $RBAC;
  if ( !is_object( $G_PUBLISH ) ) die ("Publisher object is required by this template!");
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<?php
  if (isset($_SESSION['G_MESSAGE_TYPE']) && isset($_SESSION['G_MESSAGE'])) {
    echo('<b>' . G::capitalize($_SESSION['G_MESSAGE_TYPE']) . '</b> : ' . $_SESSION['G_MESSAGE']);
    unset($_SESSION['G_MESSAGE_TYPE']);
    unset($_SESSION['G_MESSAGE']);
  }
  if( is_array( $G_PUBLISH->Parts ) )
  {
    $nplim = count( $G_PUBLISH->Parts );
    for( $npcount = 0; $npcount < $nplim; $npcount++ )
    {
      print( "<tr>\n<td align=\"center\">\n" );
      if (isset($RBAC->userObj))
      	$G_PUBLISH->RenderContent( $npcount, ($RBAC->userCanAccess('WF_SHOW_XMLFORM_NAME')==1?true:false) );
      else
      	$G_PUBLISH->RenderContent( $npcount );
      print( "</td>\n</tr>\n" );
    }
  }
?>
</table>
