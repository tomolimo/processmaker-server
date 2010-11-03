<?php
  unset($_SESSION['__currentTabDashboard']);
  if(isset($_GET['t'])){
	  $_SESSION['__currentTabDashboard']=$_GET['t'];
  }

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');
  $oHeadPublisher->usingExtJs('ux/TabCloseMenu');
  //$oHeadPublisher->usingExtJs('ux/ColumnHeaderGroup');
  $oHeadPublisher->addExtJsScript('cases/casesStartPage', false);    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/casesStartPage'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');