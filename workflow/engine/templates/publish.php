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
<?php	
if (isset($_SESSION['G_MESSAGE_TYPE']) && isset($_SESSION['G_MESSAGE'])) {
    $messageTypes=array("TMP-INFO","INFO","TMP-WARNING", "WARNING", "TMP-ERROR", "ERROR");
    
    if(in_array(strtoupper($_SESSION['G_MESSAGE_TYPE']),$messageTypes)){
        $msgType=strtoupper($_SESSION['G_MESSAGE_TYPE']);
    }else{
        $msgType="WARNING";
    }

    $timeToHideTmpMsg = "";
    if( substr($msgType,0,3) == 'TMP'){
      if(isset($_SESSION['G_MESSAGE_TIME'])){
        $timeToHideTmpMsg = $_SESSION['G_MESSAGE_TIME'];
        unset($_SESSION['G_MESSAGE_TIME']);
      } else {
        $timeToHideTmpMsg = "5";
      }
      $msgType = str_replace('TMP-', '', $msgType);
    }
    
    switch($msgType){
      case "WARNING": $msg = G::LoadTranslation("ID_WARNING"); break;
      case "ERROR":   $msg = G::LoadTranslation("ID_ERROR"); break;
      case "INFO":    $msg = G::LoadTranslation("ID_INFO"); break;
      default: $msg = G::LoadTranslation("ID_INFO"); break;
    }

    if( isset($_SESSION['G_MESSAGE_WIDTH']) ){
      if(strpos($_SESSION['G_MESSAGE_WIDTH'], '%') !== false )
        $G_MSG_WIDTH = $_SESSION['G_MESSAGE_WIDTH'];
      else {
        if( is_int($_SESSION['G_MESSAGE_WIDTH']) && $_SESSION['G_MESSAGE_WIDTH'] <= 100 ){
          $G_MSG_WIDTH = "{$_SESSION['G_MESSAGE_WIDTH']}%";
        } else {
          $G_MSG_WIDTH = '65%';
        }
      }
      unset($_SESSION['G_MESSAGE_WIDTH']);
    } else {
      $G_MSG_WIDTH = '65%';
    }

    echo '<table width="'.$G_MSG_WIDTH.'" cellpadding="5" cellspacing="0" border="0">';
    echo '<tr><td id="temporalMessageTD" class="temporalMessage'.$msgType.'" align="center">';
    echo '<div id="temporalMessage'.$msgType.'"><strong>';
    echo $msg . '</strong>: ' . $_SESSION['G_MESSAGE'] . '</div></td></tr>';
	  echo '</table><script>PMOS_TemporalMessage('.$timeToHideTmpMsg.')</script>';

    unset($_SESSION['G_MESSAGE_TYPE']);
    unset($_SESSION['G_MESSAGE']);
}
?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-top: 3px">
<?php  
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
