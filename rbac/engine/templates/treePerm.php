<?php
/**
 * treePerm.php
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
  global $canCreatePerm;


  $appid = $_SESSION['CURRENT_APPLICATION'];

  $pathViewChart = "";
  $nodo = isset($_GET['UID'])?$_GET['UID']:'';
  if ($nodo == "") $pathViewChart = "";

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
  $appCode = G::LoadMessage (12);
  print "<center class='subtitle'>$appCode</center>";

  $ses = new DBSession;
  $ses->SetTo ($dbc);

  function showLevel ( $i, $label1, $texto1, $texto2, $uid) {
   global $pathViewChart;
   global $collapsed;
   global $appid;
   global $dbc;
   global $canCreatePerm;

    $ses = new DBSession;
    $ses->SetTo ($dbc);

   $MAX_LEVEL = 10;
   $sql = "SELECT count(*) as CANT from PERMISSION WHERE PRM_APPLICATION = $appid AND PRM_PARENT = $uid ";
   $dset = $ses->Execute($sql);
   $row2 = $dset->Read();

   $icon = "browse";
   if ($row2['CANT'] > 0 ) $icon = "minus";
   if ( in_array ( $uid, $collapsed) ) $icon = "plus";

   $link = "<img src='/images/$icon.gif' border='0'>";
   if ($icon != "browse" )
     $link = "<a href='" . $pathViewChart ."permList?UID=$uid'><img src='/images/$icon.gif' border=0></a>";

   print "<tr height=22 valign=top>";
   for ( $j = 1; $j <= $i; $j ++)
     print "<td background='/images/ftv2vertline.gif'></td>";

   print "<td valign=center>" . $link . "</td>";
   print "<td valign=center colspan = '" . ($MAX_LEVEL - $i) . "'>&nbsp; <small>";
   if ($canCreatePerm==1)
     print "<a href='" . $pathViewChart . "permEdit?UID=" . $uid . "'>$texto1" ;
   else
     print "<b>$texto1</b>";
   if ($canCreatePerm==1)  print "</a>";
   print "  " . $texto2 . "</small>  ";

   if ($canCreatePerm==1) {
     print "<a href='" . $pathViewChart . "permNew?UID=" . $uid . "' ><img src='/images/form.gif' border=0></a> ";
     if ($icon == "browse" )
       print "<a href='" . $pathViewChart . "permDel?UID=" . $uid . "' ><img src='/images/trash.gif' border=0></a>";
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
    $sql = "SELECT UID, PRM_CODE, PRM_DESCRIPTION from PERMISSION WHERE PRM_APPLICATION = $appid AND PRM_PARENT = " . $parent ;
    $dset = $ses->Execute($sql);
    $row = $dset->Read();

    $c = 1;
    while ( is_array ($row) ) {

      if ($label  === "*" )
        { $label = ""; $locLabel = $c; }
      else
        $locLabel = $label . "." . $c;

      showLevel ( $level    , $locLabel, $row['PRM_CODE'], $row['PRM_DESCRIPTION'], $row['UID'] );

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
