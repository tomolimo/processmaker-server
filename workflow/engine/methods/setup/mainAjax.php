<?php

  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'loadMenu':
      if( ! isset($_GET['menu']) ) {
        exit(0);
      }

      global $G_TMP_MENU;
      $oMenu = new Menu();
      $oMenu->load('setup');
      $items = Array();
      
      foreach( $oMenu->Options as $i=>$option) {
        if( $oMenu->Types[$i] == $_GET['menu'] ){
          $items[] = Array(
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
        } else if( ! in_array($oMenu->Types[$i], Array('tool', 'maintenance')) && $_GET['menu'] == 'setting' ){
          $items[] = Array(
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
      
      if( isset($_SESSION['DEV_FLAG']) && $_SESSION['DEV_FLAG'] && $_GET['menu'] == 'tool' ){
        $items[] = Array(
          'id'    => 'translations',
          'url'  => '../tools/translations',
          'text' => 'Translations',
          'loaded' => true,
          'leaf'   => true,
          'cls'    => 'pm-tree-node',
          'iconCls'=> 'ICON_'
        );
      }
      
      echo G::json_encode($items);
    break;

  }

  