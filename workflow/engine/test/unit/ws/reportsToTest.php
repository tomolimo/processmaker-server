<?php
/**
 * reportsToTest.php.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */
   //checking the processList

  //Archivos requeridos
  if ( !defined ('PATH_THIRDPARTY') ) {
    require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
  }
  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  $aGroups      = file_get_values ( 'groups.txt' );
  $aFirstname   = file_get_values ( 'firstname.txt' );
  $aLastname    = file_get_values ( 'lastname.txt' );
  $aDepartments = file_get_departments ( 'departments.txt' );
  
  $iGroups      = count ( $aGroups );
  $iFirstname   = count ( $aFirstname );
  $iLastname    = count ( $aLastname );
  $iDepartments = count ( $aDepartments );

  global $sessionId;
  global $client;
  global $roleOperator;

  $t = new lime_test( 570, new lime_output_color());
  $t = new lime_test( 7 + $iGroups*2 + $iFirstname*5 + $iDepartments*2,  new lime_output_color());
  $t->diag('Reports To Test' );


  $t->is( function_exists('ws_open')  , true, 'include wsClient.php' );

  $t->diag('WS WSDL URL ' . WS_WSDL_URL );
  $t->diag('WS_USER_ID ' . WS_USER_ID );
  $t->diag('WS_USER_PASS ' . WS_USER_PASS );
  ws_open ();

  $t->isa_ok( $client , 'SoapClient',  'class SoapClient created');

  //checking the groupList
  $groups    = ws_groupList() ;
  $t->is( count ($groups->groups) >= 0 , true,  'ws_groupList works fine');

  $groupId = array();
  foreach ( $aGroups as $key => $val ) {  
    $groupId[] = group_exists ( $t, $groups, $val );
  }

  //checking roles
  $roles     = ws_roleList() ;
  $t->is( count ($roles->roles) >= 2 , true,  'ws_roleList works fine');

  $roleOperator = '';
  foreach ( $roles->roles as $key => $val ) {
    if ( $val->name == 'PROCESSMAKER_OPERATOR' )
      $roleOperator = $val->guid;
  }
  $t->is( strlen( $roleOperator ) >= 30 , true,  'role PROCESSMAKER_OPERATOR exists');
  if ( $roleOperator == '' )
    throw ( new Exception ( "role PROCESSMAKER_OPERATOR doesn't exist" ) );
  
  //checking user list 
  $users     = ws_userList();
  $t->is( count ($users->users) >= 1 , true,  'ws_userList works fine');

  //create users                                   
  $userId = array();
  foreach ( $aFirstname as $key => $val ) {  
    $lastname = $aLastname [ rand( 0, $iLastname ) ];
    $userId[] = user_exists ( $t, $users, $val, $lastname,$roleOperator );
  }

  //assign groups                        
  for ( $i = 0; $i < count( $userId ); $i++ ) {  
    $userUid  = $userId [ $i ];
    $groupUid = $groupId [ rand( 0, count( $groupId )-1 ) ];
    assignUserToGroup ( $t, $userUid, $groupUid);
    $groupUid = $groupId [ rand( 0, count( $groupId )-1 ) ];
    assignUserToGroup ( $t, $userUid, $groupUid);
  }
                                         
  //checking the departmentList          
  $deps    = ws_departmentList() ;       
  $t->is( count ($deps->departments) >= 0 , true,  'ws_departmentList works fine');
          
  $depId = array();                               
  foreach ( $aDepartments as $key => $val ) {  
    $depId[] = department_exists ( $t, $val[0], $val[1] );
  }
 
   //assign departments
  for ( $i = 0; $i < count( $userId ); $i++ ) {  
    $usrUid  = $userId [ $i ];
    $depUid  = $depId  [ rand( 0, count( $depId )-1 ) ];
    assignUserToDepartment ( $t, $usrUid, $depUid, true );
  }

  function assignUserToGroup ( $t, $userId, $groupId ) {
    $result = ws_assignUserToGroup ( $userId, $groupId  );
    if ( $result->status_code == 8 ) {   
      $t->pass( "User $userId already exists in the group");
      return;                            
    }                                    
    if ( $result->status_code != 0 ) {   
    	throw ( new Exception ( $result->message ) );
    }                                    
    $t->pass( "assigned $userId to group $groupId");
  }                                      
                                         
  function assignUserToDepartment ( $t, $userId, $depId, $manager ) {
    $result = ws_assignUserToDepartment ( $userId, $depId, $manager  );
/*    if ( $result->status_code == 8 ) { 
      $t->pass( "User $userId already exists in the group");
      return;                            
    }                                    
*/                                       
    if ( $result->status_code != 0 ) {   
    	throw ( new Exception ( $result->message ) );
    }                                    
    $t->pass( "assigned $userId to dep $depId");
  }                                      
                                         
  //check if the group exists, if not, create it and returns the guid
  function group_exists ( $t, $groups, $groupName ) {
    $groupId = '';                       
    foreach ( $groups->groups as $key => $val ) {
      if ( $val->name == $groupName ) $groupId = $val->guid ;
    }                                    
                                         
  	//creates group                      
    if ( $groupId == '' ) {              
      $result = ws_createGroup ( $groupName );
      if ( $result->status_code != 0 ) { 
      	throw ( new Exception ( $result->message ) );
      }                                  
      $groupId = $result->groupUID;      
      $t->pass( "Group $groupName created successfully");
    }                                    
    else                                 
      $t->pass( 'Not necessary create the group ' . $groupName );
                                         
   $t->pass ( "$groupName group exists with UID = $groupId");
                                         
    return $groupId;	                   
  }                                      
                                         
  //check if the department exists, if not, create it and returns the guid
  function department_exists ( $t, $depName, $depParentName ) {
    $deps    = ws_departmentList() ;     
    $depId = '';                         
    foreach ( $deps->departments as $key => $val ) {
      if ( $val->name == $depName ) $depId = $val->guid ;
    }                                    
                                         
    $depParentId = '';                   
    foreach ( $deps->departments as $key => $val ) {
      if ( $val->name == $depParentName ) $depParentId = $val->guid ;
    }                                    
                                         
  	//creates department                 
    if ( $depId == '' ) {                
      $result = ws_createDepartment ( $depName, $depParentId );
      if ( $result->status_code != 0 ) { 
      	throw ( new Exception ( $result->message ) );
      }                                  
      $depId = $result->departmentUID;   
      $t->pass( "Department $depName created successfully");
    }                                    
    else                                 
      $t->pass( "Not necessary create the Department $depName" );
                                         
    $t->pass ( "$depName department exists with UID = $depId");
                                         
    return $depId;	                     
  }                                      
                                         
  //check if the user exists, if not, create it and returns the guid
  function user_exists ( $t, $users, $firstname, $lastname, $roleOperator ) {
    $userId = '';                        
  	$username = strtolower ($firstname); 
    foreach ( $users->users as $key => $val ) {
      if ( strtolower($val->name) == $username  ) $userId = $val->guid ;
    }                                    
  	//creates user                       
    if ( $userId == '' ) {               
    	$firstname = ucwords( $firstname );
    	$lasstname = ucwords( $lasstname );
    	$email     = $username . '@colosa.com';
    	$password  = 'sample';             
      $result = ws_createUser ( $username, $firstname, $lastname, $email, $roleOperator, $password );
      if ( $result->status_code != 0 ) { 
      	throw ( new Exception ( $result->message ) );
      }                                  
      $userId = $result->userUID;        
      $t->pass( "User $username created successfully");
    }                                    
    else                                 
      $t->pass( 'Not necessary create the user ' . $username );
                                         
    $t->pass ( "$username exists with UID = $userId");
                                         
    return $userId;	                     
  }                                      

  function file_get_values ( $fileName ) {
    $fName = PATH_CORE. 'test'.PATH_SEP.'fixtures'.PATH_SEP. $fileName;
    $array = array();
    if ( !file_exists($fName) ) 
      throw (new Exception ( "file $fName doesn't exist." ) );
        
    $fp = fopen ( $fName, 'r' );
    $i = 0;
    while ( !feof( $fp ) ) {
      $line = trim(fgets ( $fp ));
      if ( /*$i++ < 10 && */ $line != '' )  $array[] = $line;
    }
    return $array;
  }

  function file_get_departments ( $fileName ) {
    $fName = PATH_CORE. 'test'.PATH_SEP.'fixtures'.PATH_SEP. $fileName;
    $array = array();
    if ( !file_exists($fName) ) 
      throw (new Exception ( "file $fName doesn't exist." ) );
        
    $fp = fopen ( $fName, 'r' );
    $i = 0;
    while ( !feof( $fp ) ) {
      $line = trim(fgets ( $fp ) );
      if ( /*$i++ < 10 &&*/ $line != '' ) {
      	$aux = explode('|', $line );
      	$array[] = array ( trim($aux[0]) , trim($aux[1])  );
      } 
    }
    return $array;
  }
