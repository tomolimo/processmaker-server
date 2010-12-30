<?php
try
 {
    G::LoadClass('processMap');
    $oProcessMap = new processMap(new DBConnection);

    if(isset($_GET['pid']) && !isset($_GET['type'])){
        $rows        = $oProcessMap->getExtReportTables($_GET['pid']);
    }

    else if(isset($_GET['pid']) && $_GET['type'] == 'global')
    {
        $aTheFields = array();
        $aTheFields = getDynaformsVars($_GET['pid'], false);
        foreach ($aTheFields as $aField) {
          $rows[]  = array('FIELD_UID'  => $aField['sName'] . '-' . $aField['sType'],
                                     'FIELD_NAME' => $aField['sName']);
        }
    }
    
    else if(isset($_GET['pid']) && $_GET['type'] == 'grid'){
        $aTheFields = array();
        $aTheFields = getGridsVars($_GET['pid']);
        foreach ($aTheFields as $aField) {
          $rows[]  = array('FIELD_UID'  => $aField['sName'] . '-' . $aField['sXmlForm'],
                                         'FIELD_NAME' => $aField['sName']);
        }
    }

    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    print json_encode( $result ) ;

 }
  catch ( Exception $e ) {
  	print json_encode ( $e->getMessage() );
  }
?>