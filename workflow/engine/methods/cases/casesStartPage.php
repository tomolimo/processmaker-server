<?php

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');
  $oHeadPublisher->usingExtJs('ux/TabCloseMenu');
  $oHeadPublisher->usingExtJs('ux/ColumnHeaderGroup');
  $oHeadPublisher->addExtJsScript('cases/casesStartPage', true );    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/casesStartPage'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');