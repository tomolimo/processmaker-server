<?
/**
 * processes_ImportFile.php
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
 * @Author: Erik Amaru Ortiz <aortiz.erik@gmail.com, erik@colosa.com> 
 */

require_once 'classes/model/AdditionalTables.php';

try {
   
  echo '<pre>';
  //print_R($_POST['form']['OVERWRITE']);
  
  $overWrite = isset($_POST['form']['OVERWRITE'])? true: false;

  //save the file
  if ($_FILES['form']['error']['FILENAME'] == 0) {
  	$PUBLIC_ROOT_PATH = PATH_DATA.'sites'.PATH_SEP.SYS_SYS.PATH_SEP.'public'.PATH_SEP;
  	
    $filename = $_FILES['form']['name']['FILENAME'];
    $tempName = $_FILES['form']['tmp_name']['FILENAME'];
    G::uploadFile($tempName, $PUBLIC_ROOT_PATH, $filename );
    
    $fileContent = file_get_contents($PUBLIC_ROOT_PATH.$filename);
    
    if(strpos($fileContent, '-----== ProcessMaker Open Source Private Tables ==-----') !== false){
    	$oMap = new aTablesMap();
    	
    	$fp 		= fopen($PUBLIC_ROOT_PATH.$filename, "rb");
        $fsData		= intval(fread($fp, 9));    //reading the metadata
        $sType  	= fread($fp, $fsData);    //reading string $oData
        
        require_once 'classes/model/AdditionalTables.php';
		$oAdditionalTables = new AdditionalTables();
		require_once 'classes/model/Fields.php';
		$oFields = new Fields();
					
   		while ( !feof($fp) ) {
        	switch($sType){
        		case '@META':
        			$fsData	  = intval(fread($fp, 9));
        			$METADATA = fread($fp, $fsData);
        			//print_r($METADATA);
        			break;
        		case '@SCHEMA':
        			
        			$fsUid	  = intval(fread($fp, 9));
        			$uid   	  = fread($fp, $fsUid);
        			
        			$fsData	  = intval(fread($fp, 9));
        			$schema   = fread($fp, $fsData);
        			$contentSchema = unserialize($schema);
        			//print_r($contentSchema);
        			
        			if($overWrite){
        				$aTable = new additionalTables();
        				try{
	      					$tRecord = $aTable->load($uid);
	      					$aTable->deleteAll($uid);
        				} catch(Exception $e){
        					$tRecord = $aTable->loadByName($contentSchema['ADD_TAB_NAME']);
        					if($tRecord[0]){
        						$aTable->deleteAll($tRecord[0]['ADD_TAB_UID']);
        					}
        				}
        			} else {
        				#verify if exists some table with the same name
        				$aTable = new additionalTables();
        				$tRecord = $aTable->loadByName("{$contentSchema['ADD_TAB_NAME']}%");
        				
        				if($tRecord){
        					$tNameOld = $contentSchema['ADD_TAB_NAME'];
        					$contentSchema['ADD_TAB_NAME'] =  "{$contentSchema['ADD_TAB_NAME']}".sizeof($tRecord);
        					$contentSchema['ADD_TAB_CLASS_NAME'] =  "{$contentSchema['ADD_TAB_CLASS_NAME']}".sizeof($tRecord);
        					$oMap->addRoute($tNameOld, $contentSchema['ADD_TAB_NAME']); 
        				}
        				
        			}

        			$sAddTabUid = $oAdditionalTables->create(
        				array(
        					'ADD_TAB_NAME'  		  => $contentSchema['ADD_TAB_NAME'],
                            'ADD_TAB_CLASS_NAME'      => $contentSchema['ADD_TAB_CLASS_NAME'],
                            'ADD_TAB_DESCRIPTION'     => $contentSchema['ADD_TAB_DESCRIPTION'],
                            'ADD_TAB_SDW_LOG_INSERT'  => $contentSchema['ADD_TAB_SDW_LOG_INSERT'],
                            'ADD_TAB_SDW_LOG_UPDATE'  => $contentSchema['ADD_TAB_SDW_LOG_UPDATE'],
                            'ADD_TAB_SDW_LOG_DELETE'  => $contentSchema['ADD_TAB_SDW_LOG_DELETE'],
                            'ADD_TAB_SDW_LOG_SELECT'  => $contentSchema['ADD_TAB_SDW_LOG_SELECT'],
                            'ADD_TAB_SDW_MAX_LENGTH'  => $contentSchema['ADD_TAB_SDW_MAX_LENGTH'],
                            'ADD_TAB_SDW_AUTO_DELETE' => $contentSchema['ADD_TAB_SDW_AUTO_DELETE'],
					        'ADD_TAB_PLG_UID'         => $contentSchema['ADD_TAB_PLG_UID']
					    ), 
					    $contentSchema['FIELDS']
					);
					
					
					$aFields   = array();
					foreach( $contentSchema['FIELDS'] as $iRow => $aRow ){
						unset($aRow['FLD_UID']);
						$aRow['ADD_TAB_UID'] = $sAddTabUid;
					    $oFields->create($aRow);
//					    print_R($aRow); die;
					    $aFields[] = array(
	    				   'sType'       => $contentSchema['FIELDS'][$iRow]['FLD_TYPE'],
	                       'iSize'       => $contentSchema['FIELDS'][$iRow]['FLD_SIZE'],
	                       'sFieldName'  => $contentSchema['FIELDS'][$iRow]['FLD_NAME'],
	                       'bNull'       => $contentSchema['FIELDS'][$iRow]['FLD_NULL'],
	                       'bAI'         => $contentSchema['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'],
	                       'bPrimaryKey' => $contentSchema['FIELDS'][$iRow]['FLD_KEY']
					    );
					}
					$oAdditionalTables->createTable($contentSchema['ADD_TAB_NAME'], 'wf', $aFields);					

					for($i=1; $i <= count($contentSchema['FIELDS']); $i++){
						$contentSchema['FIELDS'][$i]['FLD_NULL'] = $contentSchema['FIELDS'][$i]['FLD_NULL'] == '1' ? 'on' : '';
                        $contentSchema['FIELDS'][$i]['FLD_AUTO_INCREMENT'] = $contentSchema['FIELDS'][$i]['FLD_AUTO_INCREMENT'] == '1' ? 'on' : '';
                        $contentSchema['FIELDS'][$i]['FLD_KEY'] = $contentSchema['FIELDS'][$i]['FLD_KEY'] == '1' ? 'on' : '';
                        $contentSchema['FIELDS'][$i]['FLD_FOREIGN_KEY'] = $contentSchema['FIELDS'][$i]['FLD_FOREIGN_KEY'] == '1' ? 'on' : '';
					} 
					
					$oAdditionalTables->createPropelClasses($contentSchema['ADD_TAB_NAME'], $contentSchema['ADD_TAB_CLASS_NAME'], $contentSchema['FIELDS'], $sAddTabUid);
        			
        			break;
        		case '@DATA':
        			$fstName	 = intval(fread($fp, 9));
        			$tName 		 = fread($fp, $fstName);
        			$fsData	  	 = intval(fread($fp, 9));
        			$contentData = unserialize(fread($fp, $fsData));
        			
        			$tName = $oMap->route($tName); 
        							
					$oAdditionalTables = new AdditionalTables();
        			$tRecord = $oAdditionalTables->loadByName($tName);
        			
        			if($tRecord){
						foreach($contentData as $data){
							unset($data['DUMMY']);
							$oAdditionalTables->saveDataInTable($tRecord[0]['ADD_TAB_UID'], $data);
						}
        			}
        			break;
        	}
        	$fsData	= intval(fread($fp, 9));
        	if($fsData > 0){    
        		$sType  = fread($fp, $fsData);
        	} else {
        		break;
        	}  
        }
        
        G::header("location: additionalTablesList");
        
    } else {
    	G::SendTemporalMessage ('INVALID_FILE', "Error");
	    G::header("location: additionalTablesToImport");
  	}
      
  }
} catch(Exception $e){
	echo $e;
}


class aTablesMap{
	var $aMap;
	
	function route($uid){
		if( isset($this->aMap[$uid]) ){
			return $this->aMap[$uid];
		} else {
			return $uid;
		}
	}
	
	function addRoute($item, $equal){
		$this->aMap[$item] = $equal;
	}
	
}
  