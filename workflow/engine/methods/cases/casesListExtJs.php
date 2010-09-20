<?php
  	
  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');    
  $oHeadPublisher->addExtJsScript('cases/casesList', true );    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/casesListExtJs'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
 