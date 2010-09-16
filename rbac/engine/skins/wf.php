<?
/**
 * wf.php
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
  global $HTTP_SESSION_VARS;
  global $G_MENU_SELECTED;
  global $G_MAIN_MENU;
  global $G_SUB_MENU;
  global $G_MENU;
  global $G_TEMPLATE;
  global $SYS_COLLECTION;
  global $SYS_TARGET;
  global $CURRENT_PAGE;
  G::LoadInclude ( 'ajax');
  G::LoadInclude ( 'JSForms');

?>
<html>
<head>
<title><? if ( $HTTP_SESSION_VARS['USER_NAME']  != "") print " (" .$HTTP_SESSION_VARS['USER_NAME'] . ")" ?></title>
  <meta http-equiv="PRAGMA" content="NO-CACHE" />
  <meta http-equiv="CACHE-CONTROL" content="NO-STORE" />
  <link rel="stylesheet" type="text/css" href="/skins/wf/general.css" />
  <script src="/skins/valida.js" type="text/javascript"></script>
  <script src="/skins/ajax.js" type="text/javascript"></script>
  <script src="/skins/JSForms.js" type="text/javascript"></script>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#999999" marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" rightmargin="0">
<table background="/skins/wf/topBackgr.jpg" width="100%" height="48" border="0" cellspacing="0" cellpadding="0">
    <td width="50%" rowspan="2" height="48" valign="top"><img src="/skins/wf/test.gif" width="125" height="48"><font color=white><? /*acá iba el nombre*/ ?></font></td>
    <td width="50%" height="24" valign="top" >
      <div align="right" class="title"><small><? /*= PEAR_DATABASE*/ ?></small> &nbsp; &nbsp;</div>
    </td>
  </tr>
  <tr>
    <td width="50%" height="24" valign="bottom" class="title">
      <div align="right"><? //aca iba el título ?></div>
    </td>
  </tr>
</table>
<?
  $pageSplit = explode ( '/', $CURRENT_PAGE );
  $path = '/' .$pageSplit[1] . '/' .$pageSplit[2] . '/'.$pageSplit[3] . '/';
  if( $G_MAIN_MENU != "" ) {
    $G_MENU = new Menu;
    $G_MENU->optionOn = $G_MENU_SELECTED;
    $G_MENU->Load( $G_MAIN_MENU );
    $G_MENU->Class = "mnu";
    if( is_array( $G_MENU->Options ) ) {
      print "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
      print "<tr>";
      print "<td height='26' width='50' class='mnuOff'><img src='/skins/wf/spacer.gif' width='5' height='1'></td>";
      for( $ncount = 0 ; $ncount < $G_MENU->OptionCount() ; $ncount++ )   {
        $OnOff = ($ncount==$G_MENU->optionOn ? "mnuOn": "mnuOff" );
        $target = $path . $G_MENU->Options[$ncount];
        $label  = $G_MENU->Labels[ $ncount];
		    //si es un url ABSOLUTO
		    if( $G_MENU->Types[$ncount] == "absolute" ) {
		      if (defined('ENABLE_ENCRYPT')) {
		        $target = str_replace ( 'sys' . SYS_SYS , SYS_SYS , $G_MENU->Options[$ncount] );
		      	$target = G::encryptUrlAbsolute ( $target , URL_KEY );
		      }
		      else
		      	$target = $path . $G_MENU->Options[$ncount];
		      }
        print "<td class=\"$OnOff\" height='26'><a href=\"$target\" class='mnuLink'> $label </a> "  . "</td>";
      }
      print "<td class='mnuOff' height='26' width='50'><img src='/skins/wf/spacer.gif' width='50' height='1'></td>";
      print "</tr>";
      print "</table>";
    }
  }

  if( $G_SUB_MENU != "" ) {
    $G_MENU = new Menu;
    $G_MENU->Load( $G_SUB_MENU );
    $G_MENU->Class = "subMnu";
    if( is_array( $G_MENU->Options ) ) {
      print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td height='20'><div align='center'>";
        for( $ncount = 0 ; $ncount < $G_MENU->OptionCount() ; $ncount++ )   {
          $target = $path . $G_MENU->Options[$ncount];
          $label  = $G_MENU->Labels[ $ncount];
		      //si es un url ABSOLUTO
		      if( $G_MENU->Types[$ncount] == "absolute" ) {
		        if (defined('ENABLE_ENCRYPT')) {
		          $target = str_replace ( 'sys' . SYS_SYS , SYS_SYS , $G_MENU->Options[$ncount] );
		      	  $target = G::encryptUrlAbsolute ( $target , URL_KEY );
		        }
		        else
		      	  $target = $path . $G_MENU->Options[$ncount];
		      }
           print "<a href=\"$target\" class='subMnuLink'> $label </a>&nbsp;&nbsp;&nbsp;";
           //$G_MENU->RenderOption( $ncount ); print "&nbsp;&nbsp;&nbsp;";
         }
      print "</td></tr></table>";
    }
  }
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="/skins/wf/menuShadow.gif" height="10" colspan="8"><img src="/skins/wf/spacer.gif" width="1" height="10"></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<?
  global $HTTP_SESSION_VARS;
  if (!isset($HTTP_SESSION_VARS['G_MESSAGE']))
  {
  	$HTTP_SESSION_VARS['G_MESSAGE'] = '';
  }
  if (!isset($HTTP_SESSION_VARS['G_MESSAGE_TYPE']))
  {
  	$HTTP_SESSION_VARS['G_MESSAGE_TYPE'] = '';
  }
  $msg = $HTTP_SESSION_VARS['G_MESSAGE'];
  $msgType = $HTTP_SESSION_VARS['G_MESSAGE_TYPE'];
  session_unregister ('G_MESSAGE');
  session_unregister ('G_MESSAGE_TYPE');

  if( $msg != "" ) {
  $clase = "";
  if ($msgType=="error")    $clase= "Rojo";
  if ($msgType=="info")     $clase= "Azul";
  if ($msgType=="question") $clase= "Verde";
?>
    <tr>
    <td align="center">
      <br>
        <table><tr><td class="sendMsg<?= $clase?>"><?= $msg ?></td></tr></table>
      </td>
      </tr>
<? } ?>
    <tr>
      <td align="center">
         <? if( $G_TEMPLATE != "" ) G::LoadTemplate($G_TEMPLATE); ?>
      </td>
    </tr>

 <tr>
    <td height="25" class="footerNotice">Copyright &copy; 2002 <a href="http://www.colosa.com" class="smallLink" target="_new">www.colosa.com</a>.
      All rights reserved.
      <br>
      <center class='subtitle'><small><?= SYS_SYS ?></small></center>
      </td>
 </tr>
</table>
<div id=dummy style="top:0;position:relative;visibility:hidden;">.</div>
</body>
</html>
