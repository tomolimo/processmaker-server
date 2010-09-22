<?php 
$req = $_POST['request'];
	
switch($req){
	case 'showUsers':
	
    /* 
		  $sql = "SELECT USR_UID, USR_EMAIL, CONCAT(USR_FIRSTNAME, ' ' , USR_LASTNAME) AS USR_FULLNAME FROM USERS WHERE USR_STATUS = 'ACTIVE' AND USR_EMAIL <> ''";
    */
    $sDataBase = 'database_' . strtolower(DB_ADAPTER);
    if(G::LoadSystemExist($sDataBase)){
      G::LoadSystem($sDataBase);
      $oDataBase = new database();
      $sConcat = $oDataBase->concatString("USR_FIRSTNAME", "' '" , "USR_LASTNAME") ;
    }
		$sql = " SELECT USR_UID, USR_EMAIL, " . 
		       $sConcat .
		       " AS USR_FULLNAME FROM USERS " .
		       " WHERE USR_STATUS = 'ACTIVE' AND USR_EMAIL <> ''";
		       
		$oCriteria = new Criteria('workflow');
		$del = DBAdapter::getStringDelimiter();

		$con = Propel::getConnection("workflow");
		$stmt = $con->prepareStatement($sql);
		$rs = $stmt->executeQuery();

      	$aRows[] = array('USR_UID'=>'char', 'USR_EMAIL'=>'char', 'USR_FULLNAME'=>'char');
		while($rs->next()){
			$aRows[] = array('USR_UID'=>$rs->getString('USR_UID'), 'USR_EMAIL'=>$rs->getString('USR_EMAIL'), 'USR_FULLNAME'=>$rs->getString('USR_FULLNAME'));
		}
		//echo '<pre>';		print_r($aRows);
		
		global $_DBArray;
		$_DBArray['virtualtable']   = $aRows;
		$_SESSION['_DBArray'] = $_DBArray;
		G::LoadClass('ArrayPeer');
		$oCriteria = new Criteria('dbarray');
		$oCriteria->setDBArrayTable('virtualtable');

		$G_PUBLISH = new Publisher();
		$G_PUBLISH->AddContent('propeltable', 'paged-table', 'events/usermailList', $oCriteria);
		G::RenderPage('publish', 'raw');
	break;
	
	case 'showGroups':
		
		G::LoadClass('groups');
		$groups = new Groups();
		$allGroups= $groups->getAllGroups();
		  
		$aRows[] = array('GRP_UID' => 'char', 'GROUP_TITLE' => 'char');
		foreach($allGroups as $group) {
		    $UID         = htmlentities($group->getGrpUid());
			$GROUP_TITLE = strip_tags($group->getGrpTitle());
			$aRows[] = array('GRP_UID'=>$UID, 'GROUP_TITLE'=>$GROUP_TITLE);
		}
		
		global $_DBArray;
		$_DBArray['virtualtable']   = $aRows;
		$_SESSION['_DBArray'] = $_DBArray;
		G::LoadClass('ArrayPeer');
		$oCriteria = new Criteria('dbarray');
		$oCriteria->setDBArrayTable('virtualtable');

		$G_PUBLISH = new Publisher();
		$G_PUBLISH->AddContent('propeltable', 'paged-table', 'events/groupmailList', $oCriteria);
		G::RenderPage('publish', 'raw');
	break;
	
	case 'showDynavars':
		G::LoadClass('processMap');
		$oProcessMap = new processMap();
		$aDynaforms = $oProcessMap->getDynaformsList($_SESSION['PROCESS']);
		//g::pr($aDynaforms);
		G::LoadSystem('dynaformhandler');
		$aDynaformFields = Array();
		foreach($aDynaforms as $dyn){
			if( $dyn['DYN_TYPE'] == 'xmlform' ){
        if( is_file(PATH_DYNAFORM . "{$_SESSION['PROCESS']}/{$dyn['DYN_UID']}" . '.xml') ){
		        $oDynaformHandler = new dynaFormHandler(PATH_DYNAFORM . "{$_SESSION['PROCESS']}/{$dyn['DYN_UID']}" . '.xml');
		        $aDynaformFields[$dyn['DYN_TITLE']] =  $oDynaformHandler->getFieldNames();
            
        } else {
          continue;
        }
			}
		}
    
		
		//G::pr($aDynaformFields); die;
		$aRows = Array();
		$aRows[0] = Array('id'=>'char', 'dynaform'=>'char', 'name'=>'char');
		foreach($aDynaformFields as $dynName=>$aFields){
			foreach($aFields as $sField){
				array_push($aRows, Array('id'=>$sField, 'dynaform'=>$dynName, 'name'=>$sField));
			}
		}
		//g::pr($aRows); die;
		global $_DBArray;
		$_DBArray['virtualtable'] = $aRows;
		$_SESSION['_DBArray'] = $_DBArray;
		G::LoadClass('ArrayPeer');
		$oCriteria = new Criteria('dbarray');
		$oCriteria->setDBArrayTable('virtualtable');

		$G_PUBLISH = new Publisher();
		$G_PUBLISH->AddContent('propeltable', 'paged-table', 'events/dynavarsList', $oCriteria);
		G::RenderPage('publish', 'raw');
		
	break;
} 
