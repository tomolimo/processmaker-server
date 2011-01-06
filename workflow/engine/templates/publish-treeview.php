<?php
/**
 * publish-treeview.php
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
<?php	
if (isset($_SESSION['G_MESSAGE_TYPE']) && isset($_SESSION['G_MESSAGE'])) {
    $messageTypes=array("INFO","WARNING","ERROR");
    if(in_array(strtoupper($_SESSION['G_MESSAGE_TYPE']),$messageTypes)){
        $msgType=strtoupper($_SESSION['G_MESSAGE_TYPE']);
    }else{
        $msgType="WARNING";
    }
    echo('<table width="50%" cellpadding="5" cellspacing="0" border="0">');    
    echo('<tr><td class="temporalMessage'.$msgType.'" align="center"><div id="temporalMessage'.$msgType.'"><strong>' . G::capitalize($_SESSION['G_MESSAGE_TYPE']) . '</strong> : ' . $_SESSION['G_MESSAGE'] . '</div></td></tr>');
	  echo('</table>');


    unset($_SESSION['G_MESSAGE_TYPE']);
    unset($_SESSION['G_MESSAGE']);
  }
  ?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<?php
  if( is_array( $G_PUBLISH->Parts ) )
  {
    $nplim = count( $G_PUBLISH->Parts );
    print "<tr>";
    	print "<td width=270 valign=top>";
    	$G_PUBLISH->RenderContent( 0, ($RBAC->userCanAccess('WF_SHOW_XMLFORM_NAME')==1?true:false) );
    	print "</td>";
    	print "<td valign=top>";
	  	  print "<table border=0>";
	  	  for( $npcount = 1; $npcount < $nplim; $npcount++ )    {
	  	    print( "<tr>\n<td align=\"left\" valign=\"top\">\n" );
	  	    ?>
	  	    <?php
	  	    $G_PUBLISH->RenderContent( $npcount, ($RBAC->userCanAccess('WF_SHOW_XMLFORM_NAME')==1?true:false) );
	  	    ?>
	  	    <?php
	  	    print( "</td>\n</tr>\n" );
	  	  }
	  	  print "</table>";
    	print "</td>";
    print "</tr>";
  }
?>
</table>
