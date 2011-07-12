<?php
/**
 * additionalTablesAjax.php
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

if(isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'tableExists':
      G::LoadSystem('database_' . strtolower(DB_ADAPTER));
      $oDataBase = new database(DB_ADAPTER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $oDataBase->iFetchType = MYSQL_NUM;
      $oDataset = $oDataBase->executeQuery($oDataBase->generateShowTablesLikeSQL($_POST['sTableName']));
      echo $oDataBase->countResults($oDataset);
    break;
    case 'classExists':
      $sClassName     = strtolower(trim($_POST['sClassName']));
      $aDirectories   = array();
      $aClasses       = array();
      $aDirectories[] = PATH_GULLIVER;
      $aDirectories[] = PATH_THIRDPARTY;
      $aDirectories[] = PATH_RBAC;
      $aDirectories[] = PATH_CORE . 'classes' . PATH_SEP;
      foreach ($aDirectories as $sDirectory) {
        includeClasses($sDirectory, $aClasses, true);
      }
      echo (int)class_exists($sClassName);
    break;
    
    case 'exportexporView':
      global $G_PUBLISH;
      require_once ( 'classes/class.xmlfield_InputPM.php' );
      require_once 'classes/model/AdditionalTables.php';
      
      $G_PUBLISH = new Publisher();
      
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
    $oCriteria->add(AdditionalTablesPeer::ADD_TAB_UID, '', Criteria::NOT_EQUAL);
  
      $G_PUBLISH->AddContent('propeltable', 'additionalTables/paged-table', 'additionalTables/additionalTablesExportList', $oCriteria);
      G::RenderPage('publish', 'raw');
    break;
    case 'updatePageSize':
      G::LoadClass('configuration');
      $c = new Configurations();
      $arr['pageSize'] = $_REQUEST['size'];
      $arr['dateSave'] = date('Y-m-d H:i:s');
      $config = Array();
      $config[] = $arr;
      $c->aConfig = $config;
      $c->saveConfig('additionalTablesList', 'pageSize','',$_SESSION['USER_LOGGED']);
      echo '{success: true}';
    break;
    case 'updatePageSizeData':
      G::LoadClass('configuration');
      $c = new Configurations();
      $arr['pageSize'] = $_REQUEST['size'];
      $arr['dateSave'] = date('Y-m-d H:i:s');
      $config = Array();
      $config[] = $arr;
      $c->aConfig = $config;
      $c->saveConfig('additionalTablesData', 'pageSize','',$_SESSION['USER_LOGGED']);
      echo '{success: true}';
    break;
    
    case 'doExport':
      # @Author: Erik Amaru Ortiz <aortiz.erik@gmail.com>
      
      require_once 'classes/model/AdditionalTables.php';
      
    $tables = explode(',', $_POST['tables']);
    $schema = explode(',', $_POST['schema']);
    $data   = explode(',', $_POST['data']);
      
    G::LoadCLass('net');
    $net = new NET(G::getIpAddress());
    
      G::LoadClass("system");
  
      $META = " \n-----== ProcessMaker Open Source Private Tables ==-----\n".
          " @Ver: 1.0 Oct-2009\n".
          " @Processmaker version: ".System::getVersion()."\n".
          " -------------------------------------------------------\n".
          " @Export Date: ".date("l jS \of F Y h:i:s A")."\n".
          " @Server address: ".getenv('SERVER_NAME')." (".getenv('SERVER_ADDR').")\n".
           " @Client address: ".$net->hostname."\n".
          " @Workspace: ".SYS_SYS."\n".
          " @Export trace back:\n\n";
                  
  
      $EXPORT_TRACEBACK = Array();
      $c = 0;
      foreach ($tables as $uid) {
        
        $aTable = new additionalTables();
        $tRecord = $aTable->load($uid); 
        $oAdditionalTables = new additionalTables();
        $table = $oAdditionalTables->getAllData($uid);

        $rows  = $table['rows'];
        $count = $table['count'];
        
        array_push($EXPORT_TRACEBACK, Array(
          'uid' => $uid,
          'name' => $tRecord['ADD_TAB_NAME'],
          'num_regs' => sizeof($rows),
          'schema' => in_array($uid, $schema)? 'yes': 'no',
          'data' => in_array($uid, $data)? 'yes': 'no'
//          'schema' => ($schema[$c]=='Export')? 'yes': 'no',
//          'data' => ($data[$c]=='Export')? 'yes': 'no'
        ));
      }
      
      $sTrace = "TABLE UID\t\t\t\tTABLE NAME\tREGS\tSCHEMA\tDATA\n";
      foreach($EXPORT_TRACEBACK as $row){
        $sTrace .=   "{$row['uid']}\t{$row['name']}\t\t{$row['num_regs']}\t{$row['schema']}\t{$row['data']}\n";
      }
      
      $META .= $sTrace;
      
      ///////////////EXPORT PROCESS
      $PUBLIC_ROOT_PATH = PATH_DATA.'sites'.PATH_SEP.SYS_SYS.PATH_SEP.'public'.PATH_SEP;
      $filenameOnly = 'SYS-'.strtoupper(SYS_SYS)."_".date("Y-m-d").'_'.date("Hi").".pmt";
      $filename = $PUBLIC_ROOT_PATH . $filenameOnly;
      $fp = fopen( $filename, "wb");
      $bytesSaved = 0;
      
      $bufferType    = '@META';  
      $fsData      = sprintf("%09d", strlen($META));
      $fsbufferType   = sprintf("%09d", strlen($bufferType));
      $bytesSaved    += fwrite($fp, $fsbufferType);  //writing the size of $oData
      $bytesSaved    += fwrite($fp, $bufferType); //writing the $oData
      $bytesSaved    += fwrite($fp, $fsData);  //writing the size of $oData
      $bytesSaved    += fwrite($fp, $META); //writing the $oData
      
      foreach($EXPORT_TRACEBACK as $record){
          
        if($record['schema'] == 'yes'){
            $oAdditionalTables = new AdditionalTables();
        $aData = $oAdditionalTables->load($record['uid'], true);
      
        $bufferType  = '@SCHEMA';
        $SDATA     = serialize($aData);
            $fsUid      = sprintf("%09d", strlen($record['uid']));
            $fsData     = sprintf("%09d", strlen ($SDATA));
            $fsbufferType = sprintf("%09d", strlen($bufferType));
            
            $bytesSaved  += fwrite($fp, $fsbufferType);  //writing the size of $oData
          $bytesSaved  += fwrite($fp, $bufferType); //writing the $oData
            $bytesSaved  += fwrite($fp, $fsUid );  //writing the size of xml file
            $bytesSaved  += fwrite($fp, $record['uid'] );    //writing the xmlfile
            $bytesSaved  += fwrite($fp, $fsData);  //writing the size of xml file
            $bytesSaved  += fwrite($fp, $SDATA);    //writing the xmlfile
        }  
        
        if($record['data'] == 'yes'){
          //export data
          $oAdditionalTables = new additionalTables();
          $table = $oAdditionalTables->getAllData($uid);
        
          $rows  = $table['rows'];
          $count = $table['count'];
          
          $bufferType  = '@DATA';
          $SDATA     = serialize($rows);
          $fsUid      = sprintf("%09d", strlen($record['name']));
          $fsData     = sprintf("%09d", strlen ($SDATA));
          $fsbufferType = sprintf("%09d", strlen($bufferType));
          
          $bytesSaved  += fwrite($fp, $fsbufferType);  //writing the size of $oData
          $bytesSaved  += fwrite($fp, $bufferType); //writing the $oData
          $bytesSaved  += fwrite($fp, $fsUid );  //writing the size of xml file
          $bytesSaved  += fwrite($fp, $record['name'] );    //writing the xmlfile
          $bytesSaved  += fwrite($fp, $fsData);  //writing the size of xml file
          $bytesSaved  += fwrite($fp, $SDATA);    //writing the xmlfile
          
        }
      }
       
    fclose ($fp);  
    
    $filenameLink = "../additionalTables/doExport?f={$filenameOnly}";
    $aFields['SIZE']           = round(($bytesSaved/1024), 2)." Kb";
    $aFields['META']           = "<pre>".$META."</pre>";
    $aFields['FILENAME']        = $filenameOnly;
    $aFields['FILENAME_LINK']   = $filenameLink;
    
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'additionalTables/doExport', '', $aFields, '');
    G::RenderPage('publish', 'raw');
    
    break;

  }

}

if(isset($_POST['function'])) {
  $sfunction = $_POST['function'];
  switch($sfunction) {
    case 'existClass' :
      $result = '';
      require_once 'classes/model/AdditionalTables.php';
      $tables = explode(',', $_POST['tables']);
      $schema = explode(',', $_POST['schema']);
      $data   = explode(',', $_POST['data']);
   
      G::LoadCLass('net');
      $net = new NET(G::getIpAddress());
      G::LoadClass("system");
      $EXPORT_TRACEBACK = Array();
      foreach ($tables as $uid) {
        $aTable = new additionalTables();
        $tRecord = $aTable->load($uid); 
        $oAdditionalTables = new additionalTables();
        $ocaux = $oAdditionalTables->checkClassNotExist($uid);
        if($ocaux == null ){
          $result = $result . ' <br> ' . $tRecord['ADD_TAB_NAME'];
        }
      }
      return print $result;
      break;
  }
}

function includeClasses($sDirectory, &$aClasses, $bRecursive = false) {
  $aClassesFilter = array('class.filterForm.php',
                          'class.dvEditor.php',
                          'class.htmlArea.php',
                          'class.database_base.php',
                          'class.error.php',
                          'class.xmlMenu.php',
                          'class.form.php',
                          'class.xmlform.php',
                          'class.xmlformExtension.php',
                          'pakeFileTask.class.php',
                          'class.groupUser.php',
                          'class.xmlfield_InputPM.php',
                          'class.dynaFormField.php',
                          'class.toolBar.php');
  $oDirectory = dir($sDirectory);
  while ($sObject = $oDirectory->read()) {
    if (!in_array($sObject, array('.', '..'))) {
      if (is_dir($sDirectory . PATH_SEP . $sObject)) {
        if ($bRecursive && ($sObject != 'html2ps_pdf') && ($sObject == 'propel-generator')) {
          includeClasses($sDirectory . PATH_SEP . $sObject, $aClasses, true);
        }
      }
      else {
        $aAux = pathinfo($sDirectory . PATH_SEP . $sObject);
        if (!isset($aAux['extension'])) {
          $aAux['extension'] = '';
        }
        if (strtolower($aAux['extension']) == 'php') {
          try {
            if (!in_array($aAux['basename'], $aClassesFilter)) {
              @include $sDirectory . PATH_SEP . $sObject;
            }
          }
          catch (Exception $oError) {
            //Nothing
          }
          $aClasses[] = $sObject;
        }
      }
    }
  }
}
