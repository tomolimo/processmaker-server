<?php
/**
 * triggers_Tree.php
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

try {
	G::LoadClass ( 'tree' );
	
	G::LoadClass ( 'triggerLibrary' );
	$triggerLibrary = triggerLibrary::getSingleton ();	
	$triggerLibraryO = $triggerLibrary->getRegisteredClasses ();	
	$oTree = new Tree ( );
	$oTree->nodeType = 'blank';
	$oTree->name = 'Triggers';
	$oTree->showSign = false;
	
	$oNode = & $oTree->addChild ( 'TRI_CUSTOM', '&nbsp;&nbsp;<table border="0" cellpadding="0" cellspacing="0"><tr><td nowrap valign="top"><span onclick="currentPopupWindow.remove();triggerNewCustom();" style="cursor: pointer;" >' . "<img src='/images/50px-Edit.png' width='15px' heigth='15px' valing='middle'>&nbsp;&nbsp;<b>" .G::LoadTranslation('ID_CUSTOM_TRIGGER')  . "&nbsp;</b></td></tr><tr><td class=''><span onclick='currentPopupWindow.remove();triggerNewCustom();' style='cursor: pointer;' ><small><i>".G::LoadTranslation('ID_CUSTOM_TRIGGER_DESCRIPTION')."</i></small></span></td></tr></table>", array ('nodeType' => 'parent' ) );
	
	$triggerLibraryOCount=count($triggerLibraryO);
	foreach ( $triggerLibraryO as $keyLibrary => $libraryObj ) {
		$libraryName = $libraryObj->info ['name'];
		$libraryIcon = isset ( $libraryObj->info ['icon'] ) && ($libraryObj->info ['icon'] != "") ? $libraryObj->info ['icon'] : "/images/browse.gif";
		$libraryDescription = trim ( str_replace ( "*", "", implode ( " ", $libraryObj->info ['description'] ) ) );
		$triggerCount = count ( $libraryObj->methods );
		if ($triggerCount > 0) {
			//Sort alpha
			ksort($libraryObj->methods,SORT_STRING);
			//Now the Triggers
			//Library Father (Name + Description)
			$oNode = & $oTree->addChild ( $keyLibrary, '&nbsp;&nbsp;<table border="0" cellpadding="0" cellspacing="0"><tr><td nowrap valign="top"><span onclick="tree.expand(this.parentNode);" style="cursor: pointer;" >' . "<img src='" . $libraryIcon . "' width='15px' heigth='15px' valing='middle'>&nbsp;&nbsp;<b>" . $libraryName . "&nbsp;($triggerCount)</b></td></tr><tr><td class=''><span onclick='tree.expand(this.parentNode);' style='cursor: pointer;' ><small><i>$libraryDescription</i></small></span></td></tr></table>", array ('nodeType' => 'parent' ) );
			$oNode->contracted = $triggerLibraryOCount==1?false:true;
			//Library Childs (available methods)
			foreach ( $libraryObj->methods as $methodName => $methodObject ) {
				
				$methodName = $methodObject->info ['name'];
				$methodLabel = $methodObject->info ['label'];
				$methodDescription = trim ( str_replace ( "*", "", implode ( " ", $methodObject->info ['description'] ) ) );
				
				$oAux1 = & $oNode->addChild ( $keyLibrary . "-" . $methodName, '<table><tr><td nowrap ><span style="cursor: pointer;">' . " <a class='linkInBlue' href='#' onclick='currentPopupWindow.remove();triggerNewWizard( \"$methodName\" , \"$keyLibrary\" );return;'> " . $methodLabel . ' (' . $methodName . ')' . '</a></span></td></tr><tr><td><i>' . $methodDescription . '</i><br></span></td></tr></table>', array ('nodeType' => 'child' ) );
				//$oAux1->plus       = "<span  style='cursor:pointer;display:block;width:15;height:10px;' onclick='tree.expand(this.parentNode);'></span>";
			

			//$oAux2             =& $oAux1->addChild($keyLibrary."-".$methodName."-desc", "$methodParameters", array('nodeType'=>'parent'));
			

			}
		}
	
	}
	echo $oTree->render ();


} catch ( Exception $oException ) {
	die ( $oException->getMessage () );
}
unset ( $_SESSION ['PROCESS'] );
?>