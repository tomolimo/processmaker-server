<?php
/**
 * treeRole.php
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
  global $G_TABLE;
  global $G_CONTENT;
  global $collapsed;
  global $URI_VARS;
  global $dbc;
  global $ses;
  global $pathViewChart;
  global $appid;

  $appid = $_SESSION['CURRENT_APPLICATION'];

  $pathViewChart = "";
  $nodo = isset($_GET[0])?$_GET[0]:'';
  
  
  if ( ! session_is_registered ("CHART_COLLAPSED") ) $_SESSION['CHART_COLLAPSED'] = Array ();

  $collapsed = $_SESSION['CHART_COLLAPSED'];
  if ( in_array ( $nodo, $collapsed) )
     $collapsed [ array_search ($nodo, $collapsed) ] = NULL;
  else
     array_push ( $collapsed, $nodo);

  $_SESSION['CHART_COLLAPSED'] = $collapsed;

  //Obtener nombre de la applicacion

  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );
  G::LoadClassRBAC ('applications');
  $obj = new RBAC_Application;
  $obj->SetTo ($dbc);
  $obj->Load ($appid);
  $_SESSION['STR_APP'] = $obj->Fields['APP_CODE'];;
  $appCode = G::LoadMessage (11);
  print "<center class='subtitle'>$appCode</center>";

  $ses = new DBSession;
  $ses->SetTo ($dbc);




  function showLevel ( $i, $label1, $texto1, $texto2, $uid) {
   global $pathViewChart;
   global $collapsed;
   global $appid;
   global $dbc;
   global $canCreateRole;

   $MAX_LEVEL = 10;

   $ses = new DBSession;
   $ses->SetTo ($dbc);
   $sql = "SELECT count(*) as CANT from ROLE WHERE ROL_APPLICATION = $appid AND ROL_PARENT = $uid ";
   $dset = $ses->Execute($sql);
   $row = $dset->Read();
   $rolStatus = $row['CANT'];

   $icon = "browse";
   if ($row['CANT'] > 0 ) $icon = "minus";
   if ( in_array ( $uid, $collapsed) ) $icon = "plus";

   $link = "<img src='/images/$icon.gif' border=0>";
   if ($icon != "browse" )
     $link = "<a href='" . $pathViewChart ."roleList?0=$uid'><img src='/images/$icon.gif' border=0></a>";

   print "<tr height=22 valign=top>";
   for ( $j = 1; $j <= $i; $j ++)
     print "<td background='/images/ftv2vertline.gif'></td>";

   print "<td valign=center>" . $link . "</td>";
   print "<td valign=center colspan = '" . ($MAX_LEVEL - $i) . "'>";
   print "&nbsp; <small>";
   if ($canCreateRole == 1)
     print "<a href='" . $pathViewChart . "roleEdit?0=" . $uid . "'>" . $texto1 ."</a>";
   else
     print "<b>$texto1</b>";
   print "  " . $texto2 . "</small>  ";

   print "<a href='" . $pathViewChart . "loadRoleProp2?ROL_UID=" . $uid . "' ><img src='/images/edit.gif' height=18 width =16 border=0></a> ";
   if ($canCreateRole == 1) {
     print "<a href='" . $pathViewChart . "roleNew?0=" . $uid . "' ><img src='/images/form.gif' height=18 width =16 border=0></a> ";
     if ($icon == "browse" )
       print "<a href='" . $pathViewChart . "roleDel?0=" . $uid . "' ><img src='/images/trash.gif' border=0></a>";
   }
   print "</td>";
   print "</tr>";

  }

  function walkLevel ( $level, $label, $parent ) {
    global $collapsed;
    global $appid;
    global $dbc;

    $ses = new DBSession;
    $ses->SetTo ($dbc);
    $sql = "SELECT UID, ROL_CODE, ROL_DESCRIPTION from ROLE WHERE ROL_APPLICATION = $appid AND ROL_PARENT = " . $parent ;
    $dset = $ses->Execute($sql);
    $row  = $dset->Read();

    $c = 1;
    while (  is_array ($row) ) {

      if ($label  === "*" )
        { $label = ""; $locLabel = $c; }
      else
        $locLabel = $label . "." . $c;

      showLevel ( $level    , $locLabel, $row['ROL_CODE'], $row['ROL_DESCRIPTION'], $row['UID'] );

      if ( ! in_array ( $row['UID'], $collapsed) )
      walkLevel ( $level + 1, $locLabel, $row['UID']);
      $c++;
      $row = $dset->Read();
    }
  }



?>

<table width=100%  border=0>
  <tr>
  <td align="justify"><? /*$G_CONTENT->Output ("body.header");*/ ?></td>
  </tr>
  <tr>
    <td align="center">
      <table border=0 width=650 cellspacing=0 cellpadding=0>
      <tr>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=16></td>
      <td width=506></td>
      </tr>
  <?
    walkLevel (0, "*", 0);
  ?>
    </table>
  </td>
  </tr>
</table>
