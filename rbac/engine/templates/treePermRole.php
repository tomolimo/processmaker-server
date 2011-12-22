<?php
/**
 * treePermRole.php
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
  //global $G_CONTENT;
  //global $_SESSION;
  global $collapsed;
  global $URI_VARS;

  global $pathViewChart;
  global $appid;
  global $rolid;
  global $parents;
  //global $dbc;
  global $ses;
  global $accessChangeRole;

  $appid   = $_SESSION['CURRENT_APPLICATION'];
  $rolid   = $_SESSION['CURRENT_ROLE'];
  $parents = $_SESSION['CURRENT_ROLE_PARENTS'];

  //$pathViewChart = "../";
  //$nodo = $URI_VARS[0];
  //if ($nodo == "")
    $pathViewChart = "";

  //Obtener nombre de la applicacion

  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME);
  G::LoadClassRBAC ('applications');
  G::LoadClassRBAC ('roles');
  $obj = new RBAC_Application;
  $obj->SetTo ($dbc);
  $obj->Load ($appid);
  $_SESSION['STR_APP'] = $obj->Fields['APP_CODE'];;
  $obj = new RBAC_Role;
  $obj->SetTo($dbc);
  $obj->Load($rolid);
  $_SESSION['STR_ROL'] = $obj->Fields['ROL_CODE'];

  //$appCode = G::LoadMessage (13);
  //print "<center class='subtitle'>$appCode</center>";

  $ses = new DBSession;
  $ses->SetTo ($dbc);

  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME);
  $ses = new DBSession;
  $ses->SetTo($dbc);

  function showLevel ( $i, $label1, $texto1, $texto2, $uid) {
   global $pathViewChart;
   global $collapsed;
   global $rolid;
   global $parents;
   global $dbc;
   global $ses;
   global $canCreateRole;

   $MAX_LEVEL = 10;

   $sql = "SELECT count(*) AS CANT from ROLE_PERMISSION WHERE ROL_UID in ($parents ) AND PRM_UID = $uid";
   $dset = $ses->Execute($sql);
   $row2 = $dset->Read();
   $rolStatus = $row2['CANT'];
   if ($rolStatus == 1 ) {
     $icon = "btnYellow";
     $rolStatus = 2;  //permiso heredado por roles superiores
   }
   else {
     $sql = "SELECT count(*) AS CANT from ROLE_PERMISSION WHERE ROL_UID = $rolid AND PRM_UID = $uid";
     $dset = $ses->Execute($sql);
     $row2 = $dset->Read();
     $rolStatus = $row2['CANT'];

     $icon = "btnRed";
     if ( $rolStatus != 0 ) $icon = "btnGreen";
   }

   $link = "<img src='/images/$icon.gif' border=0>";
   if ($rolStatus != 2 )  {
     $link = "";
     if ($canCreateRole == 1) $link = "<a href='" . $pathViewChart ."roleProp?0=$uid'>";
     $link .= "<img src='/images/$icon.gif' border=0>";
     if ($canCreateRole == 1) $link .= "</a>";
   }
   print "<tr>";
   for ( $j = 0; $j < $i; $j ++) print "<td width='20'>&nbsp;</td>";
   print "<td colspan = '";
   print $MAX_LEVEL - $i . "'> <small>" . $link . " ";
   //print $label . "</small>&nbsp";
   print "<small><b> $texto1 </b> $texto2 </small>";

   print "</td>";
   print "</tr>";

  }

  function walkLevel ( $level, $label, $parent ) {
    global $collapsed;
    global $appid;
    global $dbc;

    $sql = "SELECT UID, PRM_CODE, PRM_DESCRIPTION from PERMISSION WHERE PRM_APPLICATION = $appid AND PRM_PARENT = " . $parent ;
    $ses = new DBSession ($dbc);
    $dset = $ses->Execute ($sql);
    $row = $dset->Read();
    $c = 1;
    while ( is_array ($row) ) {

      if ($label  === "*" )
        { $label = ""; $locLabel = $c; }
      else
        $locLabel = $label . "." . $c;

      showLevel ( $level , $locLabel, $row['PRM_CODE'], $row['PRM_DESCRIPTION'], $row['UID'] );

      walkLevel ( $level + 1, $locLabel, $row['UID']);
      $c++;
      $row = $dset->Read();
    }
  }
?>

<table width=100%  border=0>
  <tr>
  <td align="justify"></td>
  </tr>
  <tr>
    <td align="center">
      <table width="85%" class="tblTable" border="0">
      <tr>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=20></td>
      <td width=60%></td>
      </tr>
  <?php
    walkLevel (0, "*", 0);
  ?>
	<tr>
    </table>
  </td>
  </tr>
</table>
