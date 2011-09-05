<?php
/**
 * appList.php
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
/*class XmlForm_Field_Menu extends XmlForm_Field
{
  function render( $value = NULL )
  {
    return "�asdsad";
  }
}
class filterForm extends form
{
  var $cols = 3;
  var $type = 'filterform';
  var $ajaxServer = '...';
}
class xmlMenu extends form
{
  var $type = 'xmlmenu';
}*/
  G::LoadClass("dynaform");
  G::GenericForceLogin ('RBAC_LOGIN','login/noViewPage','login/login');
  $G_MAIN_MENU = "rbac";
  $G_SUB_MENU  = "rbac.application";
  $G_MENU_SELECTED = 1;
  
  //$RBAC->userCanAccess("RBAC_LOGIN");
  //$RBAC->userCanAccess("RBAC_READONLY" );
  //$RBAC->userCanAccess("RBAC_CREATE_ROLE" );
  //$RBAC->userCanAccess("RBAC_CREATE_PERMISSION" );
  $canCreateApp = $RBAC->userCanAccess("RBAC_CREATE_APPLICATION" );
  
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );
  $G_PUBLISH = new Publisher;
  $G_PUBLISH->SetTo ($dbc);
  //$G_PUBLISH->AddContent ( "table", "paged-table", "rbac.applications.list", "rbac/myApp", "", "load");
  //$G_PUBLISH->AddContent ( "xmlform", "xmlmenu", "rbac/appMenu", "", "", "load");
  //$G_PUBLISH->AddContent ( "xmlform", "filterform", "rbac/applicationsList", "", "", "load");
  $G_PUBLISH->AddContent ( "xmlform", "pagedTable", "rbac/applicationsList", "", "", "", "../gulliver/pagedTableAjax.php");
  $content = '';
  G::RenderPage( "publish" );

?>