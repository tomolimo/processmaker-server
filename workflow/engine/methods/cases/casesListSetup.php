<?php

  $oHeadPublisher =& headPublisher::getSingleton();
    	
  $oHeadPublisher->assignNumber( 'pageSize',     20 ); //sending the page size

  $oHeadPublisher->addExtJsScript('cases/casesListSetup', true );    //adding a javascript file .js

  $availableFields = array();
  $oHeadPublisher->assignNumber( 'availableFields', json_encode($availableFields) ); 

  $oHeadPublisher->addContent( 'cases/casesListSetup'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
