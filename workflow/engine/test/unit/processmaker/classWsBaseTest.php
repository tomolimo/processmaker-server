<?php
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
 
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'wsBase');


  $obj = new WsBase ($dbc); 
  $t   = new lime_test( 49, new lime_output_color() );

  $className = WsBase;
  $className = strtolower ( substr ($className, 0,1) ) . substr ($className, 1 );
  
  $reflect = new ReflectionClass( $className );
	$method = array ( );
	$testItems = 0;
 
  foreach ( $reflect->getMethods() as $reflectmethod )  {  
  	$params = '';
  	foreach ( $reflectmethod->getParameters() as $key => $row )   {  
  	  if ( $params != '' ) $params .= ', ';
  	  $params .= '$' . $row->name;  
  	}

 		$testItems++;
  	$methods[ $reflectmethod->getName() ] = $params;
  }
  //To change the case only the first letter of each word, TIA
  $className = ucwords($className);
  $t->diag("class $className" );

  $t->isa_ok( $obj  , "wsBase",  "class $className created");

  //$t->is( count($methods) , 26,  "class $className have " . 26 . ' methods.' );

  $t->is( count($methods) , 28,  "class $className have " . 28 . ' methods.' );
   //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( );
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using  ");


  //checking method 'login'
  $t->can_ok( $obj,      'login',   'login() is callable' );

  //$result = $obj->login ( $userid, $password);
  //$t->isa_ok( $result,      'NULL',   'call to method login ');
  $t->todo( "call to method login using $userid, $password ");


  //checking method 'processList'
  $t->can_ok( $obj,      'processList',   'processList() is callable' );

  //$result = $obj->processList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method processList ');
  $t->todo( "call to method processList using  ");


  //checking method 'roleList'
  $t->can_ok( $obj,      'roleList',   'roleList() is callable' );

  //$result = $obj->roleList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method roleList ');
  $t->todo( "call to method roleList using  ");


  //checking method 'groupList'
  $t->can_ok( $obj,      'groupList',   'groupList() is callable' );

  //$result = $obj->groupList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method groupList ');
  $t->todo( "call to method groupList using  ");


  //checking method 'caseList'
  $t->can_ok( $obj,      'caseList',   'caseList() is callable' );

  //$result = $obj->caseList ( $userId);
  //$t->isa_ok( $result,      'NULL',   'call to method caseList ');
  $t->todo( "call to method caseList using $userId ");


  //checking method 'userList'
  $t->can_ok( $obj,      'userList',   'userList() is callable' );

  //$result = $obj->userList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method userList ');
  $t->todo( "call to method userList using  ");


  //checking method 'triggerList'
  $t->can_ok( $obj,      'triggerList',   'triggerList() is callable' );

  //$result = $obj->triggerList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method triggerList ');
  $t->todo( "call to method triggerList using  ");


  //checking method 'taskList'
  $t->can_ok( $obj,      'taskList',   'taskList() is callable' );

  //$result = $obj->taskList ( $userId);
  //$t->isa_ok( $result,      'NULL',   'call to method taskList ');
  $t->todo( "call to method taskList using $userId ");


  //checking method 'sendMessage'
  $t->can_ok( $obj,      'sendMessage',   'sendMessage() is callable' );

  //$result = $obj->sendMessage ( $caseId, $sFrom, $sTo, $sCc, $sBcc, $sSubject, $sTemplate, $appFields);
  //$t->isa_ok( $result,      'NULL',   'call to method sendMessage ');
  $t->todo( "call to method sendMessage using $caseId, $sFrom, $sTo, $sCc, $sBcc, $sSubject, $sTemplate, $appFields ");


  //checking method 'getCaseInfo'
  $t->can_ok( $obj,      'getCaseInfo',   'getCaseInfo() is callable' );

  //$result = $obj->getCaseInfo ( $caseId, $iDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method getCaseInfo ');
  $t->todo( "call to method getCaseInfo using $caseId, $iDelIndex ");


  //checking method 'createUser'
  $t->can_ok( $obj,      'createUser',   'createUser() is callable' );

  //$result = $obj->createUser ( $userId, $firstname, $lastname, $email, $role, $password);
  //$t->isa_ok( $result,      'NULL',   'call to method createUser ');
  $t->todo( "call to method createUser using $userId, $firstname, $lastname, $email, $role, $password ");


  //checking method 'assignUserToGroup'
  $t->can_ok( $obj,      'assignUserToGroup',   'assignUserToGroup() is callable' );

  //$result = $obj->assignUserToGroup ( $userId, $groupId);
  //$t->isa_ok( $result,      'NULL',   'call to method assignUserToGroup ');
  $t->todo( "call to method assignUserToGroup using $userId, $groupId ");


  //checking method 'sendVariables'
  $t->can_ok( $obj,      'sendVariables',   'sendVariables() is callable' );

  //$result = $obj->sendVariables ( $caseId, $variables);
  //$t->isa_ok( $result,      'NULL',   'call to method sendVariables ');
  $t->todo( "call to method sendVariables using $caseId, $variables ");


  //checking method 'getVariables'
  $t->can_ok( $obj,      'getVariables',   'getVariables() is callable' );

  //$result = $obj->getVariables ( $caseId, $variables);
  //$t->isa_ok( $result,      'NULL',   'call to method getVariables ');
  $t->todo( "call to method getVariables using $caseId, $variables ");


  //checking method 'newCase'
  $t->can_ok( $obj,      'newCase',   'newCase() is callable' );

  //$result = $obj->newCase ( $processId, $userId, $taskId, $variables);
  //$t->isa_ok( $result,      'NULL',   'call to method newCase ');
  $t->todo( "call to method newCase using $processId, $userId, $taskId, $variables ");


  //checking method 'newCaseImpersonate'
  $t->can_ok( $obj,      'newCaseImpersonate',   'newCaseImpersonate() is callable' );

  //$result = $obj->newCaseImpersonate ( $processId, $userId, $variables);
  //$t->isa_ok( $result,      'NULL',   'call to method newCaseImpersonate ');
  $t->todo( "call to method newCaseImpersonate using $processId, $userId, $variables ");


  //checking method 'derivateCase'
  $t->can_ok( $obj,      'derivateCase',   'derivateCase() is callable' );

  //$result = $obj->derivateCase ( $userId, $caseId, $delIndex, $bExecuteTriggersBeforeAssignment);
  //$t->isa_ok( $result,      'NULL',   'call to method derivateCase ');
  $t->todo( "call to method derivateCase using $userId, $caseId, $delIndex, $bExecuteTriggersBeforeAssignment ");


  //checking method 'executeTrigger'
  $t->can_ok( $obj,      'executeTrigger',   'executeTrigger() is callable' );

  //$result = $obj->executeTrigger ( $userId, $caseId, $triggerIndex, $delIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method executeTrigger ');
  $t->todo( "call to method executeTrigger using $userId, $caseId, $triggerIndex, $delIndex ");


  //checking method 'taskCase'
  $t->can_ok( $obj,      'taskCase',   'taskCase() is callable' );

  //$result = $obj->taskCase ( $caseId);
  //$t->isa_ok( $result,      'NULL',   'call to method taskCase ');
  $t->todo( "call to method taskCase using $caseId ");


  //checking method 'processListVerified'
  $t->can_ok( $obj,      'processListVerified',   'processListVerified() is callable' );

  //$result = $obj->processListVerified ( $userId);
  //$t->isa_ok( $result,      'NULL',   'call to method processListVerified ');
  $t->todo( "call to method processListVerified using $userId ");


  //checking method 'reassignCase'
  $t->can_ok( $obj,      'reassignCase',   'reassignCase() is callable' );

  //$result = $obj->reassignCase ( $sessionId, $caseId, $delIndex, $userIdSource, $userIdTarget);
  //$t->isa_ok( $result,      'NULL',   'call to method reassignCase ');
  $t->todo( "call to method reassignCase using $sessionId, $caseId, $delIndex, $userIdSource, $userIdTarget ");


  //checking method 'systemInformation'
  $t->can_ok( $obj,      'systemInformation',   'systemInformation() is callable' );

  //$result = $obj->systemInformation ( );
  //$t->isa_ok( $result,      'NULL',   'call to method systemInformation ');
  $t->todo( "call to method systemInformation using  ");



  $t->todo (  'review all pendings methods in this class');
