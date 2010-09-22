<?php

  $action = isset($_GET['action']) ? $_GET['action']: 'default';
  G::LoadClass('case');
  G::LoadClass('configuration');

  $userId = isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : '00000000000000000000000000000000';
  switch($action) {
    case 'getAllCounters': 
      getAllCounters();  
      break;
    case 'getProcess'    : 
      getProcess();      
      break;  
    default: //this is the starting call
      getLoadTreeMenuData();
      break;
  }
  die;
  
  function getLoadTreeMenuData () {
    header ("content-type: text/xml");

    global $G_TMP_MENU;
    $oMenu = new Menu();
    $oMenu->load('cases');

    $oCases = new Cases();
    $aTypes = Array('to_do', 'draft', 'cancelled', 'sent', 'paused', 'completed','selfservice','to_revise','to_reassign');
    $aTypesID = Array('CASES_INBOX'=>'to_do', 'CASES_DRAFT'=>'draft', 'CASES_CANCELLED'=>'cancelled', 'CASES_SENT'=>'sent', 'CASES_PAUSED'=>'paused', 'CASES_COMPLETED'=>'completed','CASES_SELFSERVICE'=>'selfservice','CASES_TO_REVISE'=>'to_revise','CASES_TO_REASSIGN'=>'to_reassign');
    
    $list = array();
    $list['count']  = ' ';
     
    $empty = array();
    foreach ( $aTypes as $key => $val ) {
      $empty[$val] = $list;
    }

    $aCount = $empty; //$oCases->getAllConditionCasesCount($aTypes, true);
    $processNameMaxSize = 20;

    //now drawing the treeview using the menu options from menu/cases.php
    $menuCases = array();
    foreach( $oMenu->Options as $i => $option ) {
      if( $oMenu->Types[$i] == 'blockHeader' ) {
        $CurrentBlockID = $oMenu->Id[$i];
        $menuCases[$CurrentBlockID]['blockTitle'] = $oMenu->Labels[$i];
      }
      else {
        if (!isset($CurrentBlockID)) $CurrentBlockID = "_ORPHAN_CHILDS";
        $menuCases[$CurrentBlockID]['blockItems'][$oMenu->Id[$i]] = Array (
          'label' => $oMenu->Labels[$i],
          'link'  => $oMenu->Options[$i],
          'icon'  => (isset($oMenu->Icons[$i]) && $oMenu->Icons[$i] != '') ? $oMenu->Icons[$i] : 'kcmdf.png'
        );

        if( isset($aTypesID[$oMenu->Id[$i]]) ) {
          $menuCases[$CurrentBlockID]['blockItems'][$oMenu->Id[$i]]['cases_count']     = $aCount[$aTypesID[$oMenu->Id[$i]]]['count'];
        }
      }
    }

    //now build the menu in xml format
    $xml = '<menu_cases>';
    /* commented because the orphan will not exists
    if (isset($menuCases['_ORPHAN_CHILDS'])){
      foreach( $menuCases['_ORPHAN_CHILDS'] as $id1 => $aMenu1 ) {
        foreach( $aMenu1 as $id => $aMenu ) {
          $nofifier = '';
          $xml .= '<option title="'.$aMenu['label'].'" id="'.$id.'" '.$nofifier.' url="'.$aMenu['link'].'">';
          if( isset($aMenu['sumary']) && $aMenu['sumary'] !== '') {
            foreach($aMenu['sumary'] as $process) {
              $xml .= str_replace('&', '&amp;', $process['name']) . ' ('.$process['count'].')' . "\n";
            }
          }
          $xml .= '</option>';
        }
      }
      unset($menuCases['_ORPHAN_CHILDS']);
    }*/

    $i = 0;
    foreach( $menuCases as $menu => $aMenuBlock ) {
      if( isset($aMenuBlock['blockItems']) && sizeof($aMenuBlock['blockItems']) > 0 ) {

        $xml .= '<menu_block blockTitle="'.$aMenuBlock['blockTitle'].'" id="'.$menu.'">';

        foreach( $aMenuBlock['blockItems'] as $id => $aMenu ) {
          $i++;
          if( isset($aMenu['cases_count']) && $aMenu['cases_count'] !== '') {
            $nofifier = "cases_count=\"{$aMenu['cases_count']}\" ";
          } 
          else {
            $nofifier = '';
          }

          $xml .= '<option title="'.$aMenu['label'].'" id="'.$id.'" '.$nofifier.' url="'.$aMenu['link'].'">';
          $xml .= '</option>';
        }
        $xml .= '</menu_block>';
      }
    }
    $xml .= '</menu_cases>';

    print $xml; 
  }
  
  // get the process summary of specific case list type,
  function getProcess () {
  	global $G_TMP_MENU;
  	global $userId;
    if ( !isset($_GET['item']) ) {
      die;
    }

    $oMenu = new Menu();
    $oMenu->load('cases');
    $type = $_GET['item'];
    $oCases = new AppCacheView();
      
    $aTypesID = Array('CASES_INBOX'=>'to_do', 'CASES_DRAFT'=>'draft', 'CASES_CANCELLED'=>'cancelled', 'CASES_SENT'=>'sent', 'CASES_PAUSED'=>'paused', 'CASES_COMPLETED'=>'completed','CASES_SELFSERVICE'=>'selfservice','CASES_TO_REVISE'=>'to_revise','CASES_TO_REASSIGN'=>'to_reassign');

    $aCount = $oCases->getAllCounters(Array($aTypesID[$type]), $userId, true);
//print_r ( $aCount);
    $response = Array();
//disabling the summary...
/*    
    $i=0;
    foreach($aCount[$aTypesID[$type]]['sumary'] as $PRO_UID=>$process){
      //{"text":"state","id":"src\/state","cls":"folder", loaded:true},
      $response[$i] = new stdClass();
      $response[$i]->text = $process['name'] . ' ('.$process['count'].')';
      $response[$i]->id = $process['name'];
      $response[$i]->cls = 'folder';
      $response[$i]->loaded = true;
      $i++;
    }
*/
    //ordering
    /*for($i=0; $i<=count($response)-1; $i++){
      for($j=$i+1; $j<=count($response); $j++){

        echo $response[$j]->text .'<'. $response[$i]->text;
        if($response[$j]->text[0] < $response[$i]->text[0]){
          $x = $response[$i];
          $response[$i] = $response[$j];
          $response[$j] = $x;
        }
      }
    }*/
    echo G::json_encode($response);
  }
  
  function getAllCounters() {
  	$userUid = ( isset($_SESSION['USER_LOGGED'] ) && $_SESSION['USER_LOGGED'] != '' ) ? $_SESSION['USER_LOGGED'] : null;
    $oAppCache = new AppCacheView();
    //$aTypes = Array('to_do', 'draft', 'cancelled', 'sent', 'paused', 'completed','selfservice','to_revise','to_reassign');
    $aTypes = Array('to_do'=>'CASES_INBOX', 'draft'=>'CASES_DRAFT', 'cancelled'=>'CASES_CANCELLED', 'sent'=>'CASES_SENT', 'paused'=>'CASES_PAUSED', 'completed'=>'CASES_COMPLETED','selfservice'=>'CASES_SELFSERVICE','to_revise'=>'CASES_TO_REVISE','to_reassign'=>'CASES_TO_REASSIGN');

    $aCount = $oAppCache->getAllCounters( array_keys($aTypes), $userUid );

    $response = Array();
    $i = 0;
    foreach ($aCount as $type=>$count) {
      $response[$i]->item = $aTypes[$type];
      $response[$i]->count = $count;
      $i++;
    }
    echo G::json_encode($response);
  }    
  