<?php
/**
 * authentication.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
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
  /*
   * Authentication for Case Tracker
   *
   * @author Everth S. Berrios Morales <everth@colosa.com>
   *
   */


  if (!isset($_POST['form']) ) {
    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    G::header  ("location: login.php");die;
  }


try {
	$frm = $_POST['form'];
	$case = '';
	$pin = '';

	if (isset($frm['CASE'])) {
	  $case = strtolower(trim($frm['CASE']));
	  $pin = trim($frm['PIN']);
	}

	G::LoadClass('case');
	$oCase = new Cases();

  $uid = $oCase->verifyCaseTracker($case, $pin);
	//print_r($uid); die;
	switch ($uid) {
		//The case not exists
	  case -1:
	    G::SendTemporalMessage ('ID_CASE_NOT_EXISTS', "error");
	    break;
	  //The pin is invalid
	  case -2:
	    G::SendTemporalMessage ('ID_PIN_INVALID', "error");
	    break;
	}

	if ($uid < 0 ) {
	  G::header  ("location: login.php");
	  die;
	}

	if(is_array($uid))
	{
		require_once ("classes/model/CaseTracker.php");
		require_once ("classes/model/CaseTrackerObject.php");
		$_SESSION['CASE']=$case;
		$_SESSION['PIN']=$pin;
		$_SESSION['PROCESS']=$uid['PRO_UID'];
		$_SESSION['APPLICATION']=$uid['APP_UID'];
		$_SESSION['TASK']=-1;
		$_SESSION['INDEX']=-1;
		$a=0;
		$b=0;
		$c=0;
		$oCriteria = new Criteria();
    $oCriteria->add(CaseTrackerPeer::PRO_UID, $_SESSION['PROCESS']);
		$oCaseTracker = new CaseTracker();
		if (CaseTrackerPeer::doCount($oCriteria) === 0) {
      $aCaseTracker = array('PRO_UID'               => $_SESSION['PROCESS'],
                            'CT_MAP_TYPE'           => 'PROCESSMAP',
                            'CT_DERIVATION_HISTORY' => 1,
                            'CT_MESSAGE_HISTORY'    => 1);
      $oCaseTracker->create($aCaseTracker);
    }
    else {
		  $aCaseTracker = $oCaseTracker->load($_SESSION['PROCESS']);
		}

		if(is_array($aCaseTracker))
		{	if($aCaseTracker['CT_MAP_TYPE']!='NONE')
			 {	 $a=1;
			 		 G::header  ('location: tracker_ViewMap');
			 		 die;
		   }

			$oCriteria = new Criteria();
      $oCriteria->add(CaseTrackerObjectPeer::PRO_UID, $_SESSION['PROCESS']);
      if (CaseTrackerObjectPeer::doCount($oCriteria) > 0)
     	 {	$b=1;
     	 	  G::header  ("location: tracker_DynaDocs");
     	 	  die;
       }

			if($aCaseTracker['CT_DERIVATION_HISTORY']==1)
				 {	$c=1;
				 	  G::header  ("location: tracker_History");
				 	  die;
				 }

			G::header  ("location: tracker_No");
	  }
  }

}

catch ( Exception $e ) {
  $aMessage['MESSAGE'] = $e->getMessage();
  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  G::RenderPage( 'publish' );
  die;
}