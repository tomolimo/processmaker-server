<?php

  $oHeadPublisher =& headPublisher::getSingleton();
    	
  $oHeadPublisher->assignNumber( 'pageSize',     20 ); //sending the page size

  $oHeadPublisher->addExtJsScript('cases/casesListSetup', true );    //adding a javascript file .js
/*
  $availableFields = array();
  $availableFields[] = array( 'name' => 'APP_UID',               'column1' =>  '0', 'column2' => '0' );
  $availableFields[] = array( 'name' => 'APP_NUMBER',            'column1' =>  '1', 'column2' => '1' );
  $availableFields[] = array( 'name' => 'APP_STATUS',            'column1' =>  '2', 'column2' => '2' );
  $availableFields[] = array( 'name' => 'DEL_INDEX',             'column1' =>  '3', 'column2' => '3' );
  $availableFields[] = array( 'name' => 'APP_TITLE',             'column1' =>  '4', 'column2' => '4' );
  $availableFields[] = array( 'name' => 'APP_PRO_TITLE',         'column1' =>  '5', 'column2' => '5' );
  $availableFields[] = array( 'name' => 'APP_TAS_TITLE',         'column1' =>  '6', 'column2' => '6' );
  $availableFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'column1' =>  '7', 'column2' => '7' );
  $availableFields[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'column1' =>  '8', 'column2' => '8' );
  $availableFields[] = array( 'name' => 'APP_UPDATE_DATE',       'column1' =>  '9', 'column2' => '9' );
  $availableFields[] = array( 'name' => 'DEL_PRIORITY',          'column1' => '10', 'column2' =>'10' );

  $oHeadPublisher->assignNumber( 'availableFields', json_encode($availableFields) ); 
*/
  $availableFields = array();
  $oHeadPublisher->assignNumber( 'availableFields', json_encode($availableFields) ); 

  $oHeadPublisher->addContent( 'cases/casesListSetup'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
