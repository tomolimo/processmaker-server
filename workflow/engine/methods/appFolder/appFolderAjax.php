<?php

//if (($RBAC_Response=$RBAC->userCanAccess("PM_USERS"))!=1) return $RBAC_Response;
G::LoadInclude ( 'ajax' );
$_POST ['action'] = get_ajax_value ( 'action' );

require_once ("classes/model/AppFolder.php");

$oPMFolder = new AppFolder ( );

$rootFolder = "/";
switch ($_POST ['action']) {
	
	case 'expandNode':
	  $folderList = $oPMFolder->getFolderList ( $_POST ['node'] != 'root' ? $_POST ['node'] == 'NA' ? "" : $_POST ['node'] : $rootFolder );
	  $folderContent = $oPMFolder->getFolderContent ( $_POST ['node'] != 'root' ? $_POST ['node'] == 'NA' ? "" : $_POST ['node'] : $rootFolder );
	  //G::pr($folderContent);
	  $processListTree=array();
	  foreach ( $folderList as $key => $obj ) {
		  $tempTree ['text'] = $obj['FOLDER_NAME'];
        $tempTree ['id'] = $obj['FOLDER_UID'];
        $tempTree ['folderID'] = $obj['FOLDER_UID'];
        $tempTree ['cls'] = 'folder';
        $tempTree ['draggable'] = true;
        //$tempTree ['leaf'] = true;
        //$tempTree ['optionType'] = "category";
        //$tempTree['allowDrop']=false;
        $tempTree ['singleClickExpand'] = true;
        /*
        if ($key != "No Category") {
          $tempTree ['expanded'] = true;
        } else {
          //$tempTree ['expanded'] = false;
          $tempTree ['expanded'] = true;
        }
        */
        $processListTree [] = $tempTree;
    }
    foreach ( $folderContent as $key => $obj ) {
		  $tempTree ['text'] = $obj['APP_DOC_FILENAME'];
        $tempTree ['id'] = $obj['APP_DOC_UID'];
        
        $tempTree ['cls'] = 'file';
        //$tempTree ['draggable'] = true;
        $tempTree ['leaf'] = true;
        //$tempTree ['optionType'] = "category";
        //$tempTree['allowDrop']=false;
        //$tempTree ['singleClickExpand'] = true;
        /*
        if ($key != "No Category") {
          $tempTree ['expanded'] = true;
        } else {
          //$tempTree ['expanded'] = false;
          $tempTree ['expanded'] = true;
        }
        */
        $processListTree [] = $tempTree;
    }
    print G::json_encode ( $processListTree );
	break;
	case 'openPMFolder' :
		$WIDTH_PANEL = 350;
		G::LoadClass ( 'tree' );
		$folderContent = $oPMFolder->getFolderList ( $_POST ['folderID'] != '0' ? $_POST ['folderID'] == 'NA' ? "" : $_POST ['folderID'] : $rootFolder );
		//krumo($folderContent);
		if (! is_array ( $folderContent )) {
			echo $folderContent;
			exit ();
		}
		
		$tree = new Tree ( );
		$tree->name = 'DMS';
		$tree->nodeType = "blank";
		
		//$tree->width="350px";
		$tree->value = '';
		$tree->showSign = false;
		
		$i = 0;
		foreach ( $folderContent as $key => $obj ) {
			$i ++;
			//if($obj->item_type=="F"){
			

			$RowClass = ($i % 2 == 0) ? 'Row1' : 'Row2';
			$id_delete = G::LoadTranslation('ID_DELETE');
			$id_edit = G::LoadTranslation('ID_EDIT');
			
			$htmlGroup = <<<GHTML
	<table cellspacing='0' cellpadding='0' border='1' style='border:0px;' width="100%" class="pagedTable">
	<tr id="{$i}"  onmouseout="setRowClass(this, '{$RowClass}')" onmouseover="setRowClass(this, 'RowPointer' )" class="{$RowClass}" style="cursor:hand">
	<td width='' class='treeNode' style='border:0px;background-color:transparent;'><a href="#" onclick="focusRow(this, 'Selected');openPMFolder('{$obj['FOLDER_UID']}','{$_POST['rootfolder']}');">
	<img src="/images/folderV2.gif" border = "0" valign="middle" />&nbsp;{$obj['FOLDER_NAME']}</a>
  <a href="#" onclick="deletePMFolder('{$obj['FOLDER_UID']}','{$_POST['rootfolder']}');">&nbsp; {$id_delete}</a>
  </td>
	</tr>
	</table>
	<div id="child_{$obj['FOLDER_UID']}"></div>
GHTML;
			
			$ch = & $tree->addChild ( $key, $htmlGroup, array ('nodeType' => 'child' ) );
			$ch->point = ' ';
		
		}
		$RowClass = ($i % 2 == 0) ? 'Row1' : 'Row2';
		$key = 0;
		if ($_POST ['folderID'] == '0') {
			$notInFolderLabel = G::LoadTranslation ( 'ID_NOT_IN_FOLDER' );
			$htmlGroup = <<<GHTML
	<table cellspacing='0' cellpadding='0' border='1' style='border:0px;' width="100%" class="pagedTable">
	<tr id="{$i}" onclick="focusRow(this, 'Selected');openPMFolder('NA');" onmouseout="setRowClass(this, '{$RowClass}')" onmouseover="setRowClass(this, 'RowPointer' )" class="{$RowClass}">
	<td width='' class='treeNode' style='border:0px;background-color:transparent;'><a href="#" onclick=""><img src="/images/folderV2.gif" border = "0" valign="middle" />&nbsp;- {$notInFolderLabel} -</a>&nbsp;</td>

	</tr>
	</table>
	<div id="child_NA"></div>
GHTML;
			
			$ch = & $tree->addChild ( $key, $htmlGroup, array ('nodeType' => 'child' ) );
			$ch->point = ' ';
		}
		
		print ($tree->render ()) ;
		
		break;
	case 'getPMFolderContent' :
		$swSearch = false;
		
		if (isset ( $_POST ['folderID'] )) { //Render content of a folder
			$folderID = $_POST ['folderID'] != '0' ? $_POST ['folderID'] == 'NA' ? "" : $_POST ['folderID'] : $rootFolder;
			$folderContent = $oPMFolder->getFolderContent ( $folderID );
		} else { // Perform a Search
			$swSearch = true;
			$folderContent = $oPMFolder->getFolderContent ( NULL, array (), $_POST ['searchKeyword'], $_POST ['type'] );
		}
		array_unshift ( $folderContent, array ('id' => 'char' ) );
		if (! is_array ( $folderContent )) {
			echo $folderContent;
			exit ();
		}
		
		$_DBArray ['PM_FOLDER_DOC'] = $folderContent;
		$_SESSION ['_DBArray'] = $_DBArray;
		
		G::LoadClass ( 'ArrayPeer' );
		$c = new Criteria ( 'dbarray' );
		$c->setDBArrayTable ( 'PM_FOLDER_DOC' );
		$c->addAscendingOrderByColumn ( 'id' );
		$G_PUBLISH = new Publisher ( );
		require_once ('classes/class.xmlfield_InputPM.php');
		
		$labelFolderAddFile = "";
		$labelFolderAddFolder = "";
		if ($RBAC->userCanAccess ( 'PM_FOLDERS_ADD_FILE' ) == 1) {
			$labelFolderAddFile = G::LoadTranslation ( 'ID_ATTACH' );
		}
		if ($RBAC->userCanAccess ( 'PM_FOLDERS_ADD_FOLDER' ) == 1) {
			$labelFolderAddFolder = G::LoadTranslation ( 'ID_NEW_FOLDER' );
		}
		
		if (! $swSearch) {
			$G_PUBLISH->AddContent ( 'propeltable', 'paged-table', 'appFolder/appFolderDocumentList', $c, array ('folderID' => $_POST ['folderID'] != '0' ? $_POST ['folderID'] == 'NA' ? "/" : $_POST ['folderID'] : $rootFolder, 'labelFolderAddFile' => $labelFolderAddFile, 'labelFolderAddFolder' => $labelFolderAddFolder ) );
			$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'appFolder/appFolderDocumentListHeader', '', array (), 'appFolderList?folderID=' . $_POST ['folderID'] );
		} else {
			$G_PUBLISH->AddContent ( 'propeltable', 'paged-table', 'appFolder/appFolderDocumentListSearch', $c, array () );
			$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'appFolder/appFolderDocumentListHeader', '', array (), 'appFolderList?folderID=/' );
		}
		
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	
	case "getPMFolderTags" :
		// Default font sizes
		$min_font_size = 12;
		$max_font_size = 30;
		
		$rootFolder = "/";
		$folderID = $_POST ['rootFolder'] != '0' ? $_POST ['rootFolder'] == 'NA' ? "" : $_POST ['rootFolder'] : $rootFolder;
		$tags = $oPMFolder->getFolderTags ( $folderID );
		$minimum_count = 0;
		$maximum_count = 0;
		if ((is_array ( $tags )) && (count ( $tags ) > 0)) {
			$minimum_count = min ( array_values ( $tags ) );
			$maximum_count = max ( array_values ( $tags ) );
		}
		$spread = $maximum_count - $minimum_count;
		
		if ($spread == 0) {
			$spread = 1;
		}
		
		$cloud_html = '';
		$cloud_tags = array (); // create an array to hold tag code
		foreach ( $tags as $tag => $count ) {
			$href = "#";
			//$href="?q="$tag;
			$size = $min_font_size + ($count - $minimum_count) * ($max_font_size - $min_font_size) / $spread;
			$cloud_tags [] = '<a style="font-size: ' . floor ( $size ) . 'px' . '" class="tag_cloud" href="' . $href . '" onClick="getPMFolderSearchResult(\'' . $tag . '\',\'TAG\')"' . ' title="\'' . $tag . '\' returned a count of ' . $count . '">' . htmlspecialchars ( stripslashes ( $tag ) ) . '</a>';
		}
		$cloud_html = join ( "\n", $cloud_tags ) . "\n";
		
		print "$cloud_html";
		
		break;
	
	case "uploadDocument" :
		//krumo($_POST);
		G::LoadClass ( 'case' );
		$oCase = new Cases ( );
		
		$G_PUBLISH = new Publisher ( );
		$Fields ['DOC_UID'] = $_POST ['docID'];
		$Fields ['APP_DOC_UID'] = $_POST ['appDocId'];
		$Fields ['actionType'] = $_POST ['actionType'];
		$Fields ['docVersion'] = $_POST ['docVersion'];
		
		$Fields ['appId'] = $_POST ['appId'];
		$Fields ['docType'] = $_POST ['docType'];
		$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'cases/cases_AttachInputDocumentGeneral', '', $Fields, 'appFolderSaveDocument?UID=' . $_POST ['docID'] . '&appId=' . $_POST ['appId'] . '&docType=' . $_POST ['docType'] );
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	case "documentVersionHistory" :
		
		$folderID = $_POST ['folderID'] != '0' ? $_POST ['folderID'] == 'NA' ? "" : $_POST ['folderID'] : $rootFolder;
		$folderContent = $oPMFolder->getFolderContent ( $folderID, array ($_POST ['appDocId'] ) );
		
		array_unshift ( $folderContent, array ('id' => 'char' ) );
		if (! is_array ( $folderContent )) {
			echo $folderContent;
			exit ();
		}
		
		$_DBArray ['PM_FOLDER_DOC_HISTORY'] = $folderContent;
		$_SESSION ['_DBArray'] = $_DBArray;
		
		G::LoadClass ( 'ArrayPeer' );
		$c = new Criteria ( 'dbarray' );
		$c->setDBArrayTable ( 'PM_FOLDER_DOC_HISTORY' );
		$c->addAscendingOrderByColumn ( 'id' );
		$G_PUBLISH = new Publisher ( );
		require_once ('classes/class.xmlfield_InputPM.php');
		
		$G_PUBLISH->AddContent ( 'propeltable', 'paged-table', 'appFolder/appFolderDocumentListHistory', $c, array ('folderID' => $_POST ['folderID'] != '0' ? $_POST ['folderID'] == 'NA' ? "/" : $_POST ['folderID'] : $rootFolder ) );
		
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	
	case "uploadExternalDocument" :
		G::LoadClass ( 'case' );
		$oCase = new Cases ( );
		
		$G_PUBLISH = new Publisher ( );
		$Fields ['DOC_UID'] = "-1";
		
		$Fields ['appId'] = "00000000000000000000000000000000";
		
		$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'cases/cases_AttachInputDocumentGeneral', '', $Fields, 'appFolderSaveDocument?UID=-1&appId=' . $Fields ['appId'] . "&folderId=" . $_POST ['folderID'] );
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	
	case "newFolder" :
		$oFolder = new AppFolder ( );
		$folderStructure = $oPMFolder->getFolderStructure ( $_POST ['folderID'] );
		$Fields ['FOLDER_PATH'] = $folderStructure ['PATH'];
		$Fields ['FOLDER_PARENT_UID'] = $_POST ['folderID'];
		$Fields ['FOLDER_UID'] = G::GenerateUniqueID ();
		$G_PUBLISH = new Publisher ( );
		
		$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'appFolder/appFolderEdit', '', $Fields, 'appFolderSave' );
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	
	case "documentInfo" :
		$oFolder = new AppFolder ( );
		$Fields = $oPMFolder->getCompleteDocumentInfo ( $_POST ['appId'], $_POST ['appDocId'], $_POST ['docVersion'], $_POST ['docID'], $_POST ['usrUid'] );
		$G_PUBLISH = new Publisher ( );
		
		$G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'appFolder/appFolderDocumentInfo', '', $Fields, '' );
		G::RenderPage ( 'publish', 'raw' );
		
		break;
	
	case "documentdelete":
	include_once ("classes/model/AppDocument.php");
	$oAppDocument = new AppDocument ( );
	$oAppDocument->remove($_POST['sFileUID'],$_POST['docVersion']);
	/*we need to delete fisicaly the file use the follow code
	$appId= "00000000000000000000000000000000";
	$sPathName = PATH_DOCUMENT . $appId . PATH_SEP;
	unlink($sPathName.$_POST['sFileUID'].'_1.jpg');*/
	break;
	
	case "deletePMFolder":
	include_once ("classes/model/AppFolder.php");
	$oAppFoder = new AppFolder ( );
	$oAppFoder->remove($_POST['sFileUID'],$_POST['rootfolder']);

	break;

}