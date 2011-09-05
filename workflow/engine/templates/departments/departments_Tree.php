<?php
/**
 * departments_Tree.php
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

  //G::genericForceLogin( 'WF_MYINFO' , 'login/noViewPage', $urlLogin = 'login/login' );
  require_once 'classes/model/Department.php';

  $WIDTH_PANEL = 380;
  global $xVar;
  global $template;
  global $lastDept;
  
  $xVar = 1;
  $lastDept = array();
 
  $departmentsTreeTemplate = PATH_TPL . 'departments' . PATH_SEP . 'departments_Tree.html' ;
  $template = new TemplatePower( $departmentsTreeTemplate );
  $template->prepare();
  $template->assign( 'WIDTH_PANEL',          $WIDTH_PANEL  );
  $template->assign( 'WIDTH_PANEL_20',       $WIDTH_PANEL - 10  );
  $template->assign( 'ID_DEPARTMENTS_USERS', G::loadTranslation("ID_DEPARTMENTS_USERS") );
  $template->assign( 'ID_NEW_DEPARTMENT',    G::loadTranslation("ID_NEW") );
  
  
  
  //this is to show the link Unassigned Users 
  $template->assign( 'ADD_UNASSIGNEDUSER',  G::loadTranslation("ID_UNASSIGNED_USERS") );
  
  $htmlDpto = lookforchildren( '' , 0);
  
  print_r($htmlDpto);
  die;

  $content = $template->getOutputContent();  
  print $content;
  
//function lookforchildren(	$level, $template, $alloDeptos){
function lookforchildren(	$parent, $level){
  global $xVar;
  global $template;  
  global $lastDept;  
  $oDept = new Department();
  $allDepartments = $oDept->getDepartments ( $parent );
  
  $level = $level + 1;
  if (!isset($lastDept[$level] ) ) $lastDept[$level] = true;
  $lastDept[$level] = true;
  
  foreach( $allDepartments as $department ) {
    $xVar++;
    $depUID   = htmlentities( $department['DEP_UID'] );
    $depTitle = strip_tags( $department['DEP_TITLE'] );

    $template->newBlock( 'department');
    $template->assign( 'xVar',          $xVar );
    $template->assign( 'UID',           $depUID );
    $template->assign( 'DEPO_TITLE',    $depTitle );
    $template->assign( 'ID_EDIT',       G::LoadTranslation('ID_EDIT') );
    $template->assign( 'ID_MEMBERS',    G::LoadTranslation('ID_MEMBERS') );
    $template->assign( 'ID_NEW',        G::loadTranslation("ID_NEW") );
    for ($iLevel = 2; $iLevel <= $level; $iLevel ++ ) {
      $template->newBlock( 'level');
      $template->assign( 'UID',           $iLevel);
      if ( $iLevel == $level ) {
        if ( $department['DEP_LAST'] ) $lastDept[ $level] = false;
        $template->assign( 'image',       $department['DEP_LAST'] == 0 ? 'ftv2node'     : 'ftv2lastnode');      
        $template->assign( 'background',  $department['DEP_LAST'] == 0 ? 'ftv2vertline' : 'ftv2blank');      
      }
      else {
        $template->assign( 'image',     'ftv2blank');      
        $template->assign( 'background', $lastDept[$iLevel] ? 'ftv2vertline' : 'ftv2blank');      
      }
    }
    if ( $department['HAS_CHILDREN'] == 0) {
      $template->newBlock( 'delete');
      $template->assign( 'UID',           $depUID );
      $template->assign( 'ID_DELETE',     G::LoadTranslation('ID_DELETE') );
    }      
    lookforchildren( $depUID, $level);
  }
 
 return ;
}
