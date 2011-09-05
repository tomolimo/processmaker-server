<?php
/**
 * authentication.php
 *
 */

  if (!isset($_POST['form']) ) {
    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    G::header  ("location: login.html");die;
  }


try {
	$frm  = $_POST['form'];
	if ( isset ( $frm['USR_USERNAME'] ) ) 	{
	  $usr = strtolower( trim( $frm['USR_USERNAME']));
	  $pwd = trim( $frm['USR_PASSWORD']);
	}
  else
	{
		/*$usr = $_SESSION['USER_TEMP'];
	  $pwd = $_SESSION['PASS_TEMP'];
	  unset( $_SESSION['PASS_TEMP']);
		unset( $_SESSION['USER_TEMP']);*/
	}
	$uid  = $RBAC->VerifyLogin( $usr , $pwd);
	switch ($uid) {
		//The user not exists
	  case -1:
	    G::SendTemporalMessage ('ID_USER_NOT_REGISTERED', "warning");
	    break;
	  //The password is incorrect
	  case -2:
	    G::SendTemporalMessage ('ID_WRONG_PASS', "warning");
	    break;
	  //The user is inactive
	  case -3:
	  	G::SendTemporalMessage ('ID_USER_INACTIVE', "warning");
	  //The Due date is finished
	  case -4:
	    G::SendTemporalMessage ('ID_USER_INACTIVE', "warning");
	    break;
	}

	if ($uid < 0 ) {
	  G::header  ("location: login.html");
	  die;
	}

	$_SESSION['USER_LOGGED'] = $uid;
	$_SESSION['USR_USERNAME'] = $usr;

  // Asign the uid of user to userloggedobj
  $RBAC->loadUserRolePermission( $RBAC->sSystem, $uid );
	$res = $RBAC->userCanAccess("{siglaProjectName}_LOGIN");


	if ($res != 1 ) {
	  if ($res == -2)
	    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
	  else
	    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_PAGE', "error");
	  G::header  ("location: login.html");
	  die;
	}

  if (isset($frm['USER_LANG'])) {
  	if ($frm['USER_LANG'] != '') {
  		$lang = $frm['USER_LANG'];
  	}
  }
  else {
  	if (defined('SYS_LANG')) {
  		$lang = SYS_LANG;
  	}
  	else {
  		$lang = 'en';
  	}
  }


	$accessLogin   = $RBAC->userCanAccess("{siglaProjectName}_LOGIN");

	//administrator
	if ( $accessLogin == 1) {
    G::header('location: /sys' .  SYS_TEMP . '/' . $lang . '/' . SYS_SKIN . '/' . 'login/welcome');
	  die;
	}

	//Operador
	if ( $accessLogin == 1) {
    G::header('location: /sys' .  SYS_TEMP . '/' . $lang . '/' . SYS_SKIN . '/' . 'login/welcome');
	  die;
	}


	throw ( new Exception ( "the user $usr has no role assigned  ($res, $uid)" ) );

}
catch ( Exception $e ) {
  $aMessage['MESSAGE'] = $e->getMessage();
  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  G::RenderPage( 'publish' );
  die;
}