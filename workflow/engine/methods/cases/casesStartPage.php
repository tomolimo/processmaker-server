<?php
  unset($_SESSION['__currentTabDashboard']);
  if(isset($_GET['action'])){
	  $_SESSION['__currentTabDashboard']=$_GET['action'];
  }
  $page="";
  if(isset($_GET['action'])){
    $page=$_GET['action'];
  }

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');
  //$oHeadPublisher->usingExtJs('ux/TabCloseMenu');
      
  
  
  //$oHeadPublisher->usingExtJs('ux/ColumnHeaderGroup');
  switch($page){
    case "startCase":
      $oHeadPublisher->usingExtJs('ux.treefilterx/Ext.ux.tree.TreeFilterX');  
      $oHeadPublisher->addExtJsScript('cases/casesStartCase', false);    //adding a javascript file .js
      $oHeadPublisher->addContent( 'cases/casesStartCase'); //adding a html file  .html.
    break;
    case "documents":
      $oHeadPublisher->usingExtJs('ux.locationbar/Ext.ux.LocationBar');
      $oHeadPublisher->usingExtJs('ux.statusbar/ext-statusbar');
      $oHeadPublisher->addExtJsScript('cases/casesDocuments', false);    //adding a javascript file .js
      $oHeadPublisher->addContent( 'cases/casesDocuments'); //adding a html file  .html.
    break;
    default:
      
      $oHeadPublisher->usingExtJs('ux.treefilterx/Ext.ux.tree.TreeFilterX');
  
      $oHeadPublisher->usingExtJs('ux.locationbar/Ext.ux.LocationBar');
      $oHeadPublisher->usingExtJs('ux.statusbar/ext-statusbar');
      $oHeadPublisher->addExtJsScript('cases/casesStartPage', false);    //adding a javascript file .js
      $oHeadPublisher->addContent( 'cases/casesStartPage'); //adding a html file  .html.
    break;
      
  }
  

  G::RenderPage('publish', 'extJs');