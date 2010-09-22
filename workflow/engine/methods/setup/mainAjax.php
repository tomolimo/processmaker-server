<?php

  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'settingsMenu':      
      global $G_TMP_MENU;
      $oMenu = new Menu();
      $oMenu->load('setup');
      $toolItems = Array();
      foreach( $oMenu->Options as $i=>$option) {
        if(($oMenu->Types[$i]=='setting')){
          $toolItems[] = Array(
            'id'    => $oMenu->Id[$i],
            'url'  => ($oMenu->Options[$i]!='')? $oMenu->Options[$i]: '#',
            //'onclick' => ($oMenu->JS[$i]!='')? $oMenu->JS[$i]: '',
            'text' => $oMenu->Labels[$i],
            //'icon'  => ($oMenu->Icons[$i]!='')? $oMenu->Icons[$i]: 'icon-pmlogo.png',
            //'target'=> ($oMenu->Types[$i]=='admToolsContent')? 'admToolsContent': ''
            'loaded' => true,
            'leaf'   => true,
            'cls'    => 'pm-tree-node',
            'iconCls'=> 'ICON_'.$oMenu->Id[$i]
          );
        }
      }
      
      $items = Array();
      $items[] = Array();
      $items[] = Array();
      $items[] = Array();
      $items[] = Array();
      foreach($items as $i=>$item){
        
        $response[$i] = new stdClass();
        $response[$i]->text = 'uno';
        $response[$i]->id   = $i;
        $response[$i]->cls  = 'folder';
        $response[$i]->loaded = true;
        $response[$i]->leaf   = false;
      }
      
      echo G::json_encode($toolItems);
      break;
      
  case 'toolsMenu':
      
      global $G_TMP_MENU;
      $oMenu = new Menu();
      $oMenu->load('setup');
      $toolItems = Array();
      
      foreach( $oMenu->Options as $i=>$option) {
        if(($oMenu->Types[$i]=='tool')){
          $toolItems[] = Array(
            'id'    => $oMenu->Id[$i],
            'url'  => ($oMenu->Options[$i]!='')? $oMenu->Options[$i]: '#',
            //'onclick' => ($oMenu->JS[$i]!='')? $oMenu->JS[$i]: '',
            'text' => $oMenu->Labels[$i],
            //'icon'  => ($oMenu->Icons[$i]!='')? $oMenu->Icons[$i]: 'icon-pmlogo.png',
            //'target'=> ($oMenu->Types[$i]=='admToolsContent')? 'admToolsContent': ''
            'loaded' => true,
            'leaf'   => true,
            'cls'    => 'pm-tree-node',
            'iconCls'=> 'ICON_'.$oMenu->Id[$i]
          );
        }
      }
      
      $items = Array();
      $items[] = Array();
      $items[] = Array();
      $items[] = Array();
      $items[] = Array();
      foreach($items as $i=>$item){
        $response[$i] = new stdClass();
        $response[$i]->text = 'uno';
        $response[$i]->id   = $i;
        $response[$i]->cls  = 'folder';
        $response[$i]->loaded = true;
        $response[$i]->leaf   = false;
      }
      
      echo G::json_encode($toolItems);
    break;
  }

  